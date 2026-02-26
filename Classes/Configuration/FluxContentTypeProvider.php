<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration;

use FluidTYPO3\Flux\Content\ContentTypeManager;
use FluidTYPO3\Flux\Core;
use FluidTYPO3\Flux\Enum\FormOption;
use FluidTYPO3\Flux\Form\Container\Column;
use FluidTYPO3\Flux\Form\Container\Row;
use FluidTYPO3\Flux\Form\Container\Section;
use FluidTYPO3\Flux\Form\Container\Sheet;
use FluidTYPO3\Flux\Form\FieldInterface;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Configuration\ContentTypeProviderInterface;
use WEBcoast\Migrator\Exception\UnsupportedContentTypeException;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

readonly class FluxContentTypeProvider implements ContentTypeProviderInterface
{
    /**
     * @param ContentTypeManager $contentTypeManager
     * @param iterable|\WEBcoast\MigratorFromFlux\Configuration\Field\FieldConfigurationNormalizerInterface[] $fieldConfigurationNormalizers
     */
    public function __construct(protected ContentTypeManager $contentTypeManager, #[AutowireIterator(tag: 'webcoast.migrator_from_flux.field_configuration_normalizer')] protected iterable $fieldConfigurationNormalizers, protected IconRegistry $iconRegistry)
    {
    }

    public function getIdentifier(): string
    {
        return 'flux';
    }

    public function getDescription(): string
    {
        return 'Provides content element configurations from Flux-based content elements.';
    }

    public function getAvailableContentTypes(): iterable
    {
        // Fake backend request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['REQUEST_URI'] = '/typo3';

        $GLOBALS['TYPO3_REQUEST'] = ($GLOBALS['TYPO3_REQUEST'] ?? ServerRequestFactory::fromGlobals());
        if (!$GLOBALS['TYPO3_REQUEST']->getAttribute('applicationType')) {
            $GLOBALS['TYPO3_REQUEST'] = $GLOBALS['TYPO3_REQUEST']->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        }
        $contentTypes = [];
        foreach (Core::getRegisteredFlexFormProviders() as $provider) {
            if ($provider instanceof ProviderInterface) {
                $form = $provider->getForm([]);
                $contentTypes[] = [
                    'identifier' => $provider->getContentObjectType(),
                    'title' => $this->getLanguageService()->sL($form->getLabel()),
                    'description' => $this->getLanguageService()->sL($form->getDescription()),
                ];
            }
        }

        return $contentTypes;
    }

    public function getConfiguration(string $contentType): array
    {
        $provider = null;
        foreach (Core::getRegisteredFlexFormProviders() as $registeredProvider) {
            if ($registeredProvider instanceof ProviderInterface && $registeredProvider->getContentObjectType() === $contentType) {
                $provider = $registeredProvider;
                break;
            }
        }

        if (!$provider) {
            throw new UnsupportedContentTypeException(sprintf('No Flux provider found for content type "%s".', $contentType), 1772011181);
        }

        $form = $provider->getForm([]);
        $grid = $provider->getGrid([]);

        $configuration = [
            'title' => $this->getLanguageService()->sL($form->getLabel()),
            'description' => $this->getLanguageService()->sL($form->getDescription()),
            'iconIdentifier' => $form->getOption(FormOption::ICON),
            'group' => $form->getOption(FormOption::GROUP),
            'fields' => $this->getNormalizedFieldConfiguration($form->getChildren()),
            'grid' => $this->getNormalizedGridConfiguration($grid->getChildren()),
        ];

        return $configuration;
    }

    protected function getNormalizedFieldConfiguration(\SplObjectStorage $fields): array
    {
        $normalizedFields = [];
        foreach ($fields as $field) {
            /** @var $field FieldInterface */
            if ($field instanceof Sheet) {
                $normalizedFields[] = [
                    'identifier' => $field->getName(),
                    'type' => FieldType::TAB,
                    'title' => $this->getLanguageService()->sL($field->getLabel()),
                ];
                $normalizedFields = array_merge($normalizedFields, $this->getNormalizedFieldConfiguration($field->getChildren()));
            } else {

                if ($field instanceof Section) {
                    if (count($field->getChildren()) > 1) {
                        // Currently, we only support sections with one child for migration, as this is the most common use case. Supporting multiple children would require a more complex mapping to TCA types, which is not implemented yet.
                        throw new UnsupportedContentTypeException(sprintf('Section "%s" has more than one child, which is currently not supported for migration.', $field->getName()), 1770727355);
                    }
                    // Rewind after counting to ensure we can access the first child
                    $field->getChildren()->rewind();
                    $normalizedField = [
                        'identifier' => $field->getName(),
                        'label' => $this->getLanguageService()->sL($field->getLabel()),
                        'description' => $this->getLanguageService()->sL($field->getDescription()) ?: null, // null if empty for auto-removal
                        'type' => FieldType::SECTION,
                        'fields' => $this->getNormalizedFieldConfiguration($field->getChildren()->current()->getChildren()),
                    ];
                } else {
                    $normalizedField = [
                        'identifier' => $field->getName(),
                        'label' => $this->getLanguageService()->sL($field->getLabel()),
                        'description' => $this->getLanguageService()->sL($field->getDescription()) ?: null, // null if empty for auto-removal
                        'exclude' => $field->getExclude() ?: null, // null if false for auto-removal
                        'displayCondition' => $field->getDisplayCondition() ?: null, // null if empty for auto-removal
                    ];
                    $config = array_replace_recursive($field->buildConfiguration(), $field->getConfig());
                    foreach ($this->fieldConfigurationNormalizers as $normalizer) {
                        if ($normalizer->supports($field, $config)) {
                            $normalizedField = $normalizer->normalize($field, $normalizedField, $config);
                        }
                    }
                }

                $normalizedField = ArrayUtility::removeEmptyValuesFromArray($normalizedField);

                if (!($normalizedField['type'] ?? null)) {
                    // Do not include fields that do not have a type after normalization
                    continue;
                }

                $normalizedFields[] = $normalizedField;
            }
        }

        return $normalizedFields;
    }

    protected function getNormalizedGridConfiguration(\SplObjectStorage $rows): array
    {
        $normalizedGrid = [];
        foreach ($rows as $row) {
            if ($row instanceof Row) {
                $normalizedRow = [];
                foreach ($row->getChildren() as $column) {
                    if ($column instanceof Column) {
                        $normalizedRow[] = [
                            'name' => $column->getLabel(),
                            'colPos' => $column->getColumnPosition(),
                            'colspan' => $column->getColspan() < 2 ? null : $column->getColspan(),
                            'rowspan' => $column->getRowspan() < 2 ? null : $column->getRowspan(),
                        ];
                    }
                }
                $normalizedGrid[] = $normalizedRow;
            }
        }

        return ArrayUtility::removeEmptyValuesFromArray($normalizedGrid);
    }

    public function getFrontendTemplate(string $contentType): ?string
    {
        $provider = null;
        foreach (Core::getRegisteredFlexFormProviders() as $registeredProvider) {
            if ($registeredProvider instanceof ProviderInterface && $registeredProvider->getContentObjectType() === $contentType) {
                $provider = $registeredProvider;
                break;
            }
        }

        $templateFile = $provider->getTemplatePathAndFilename([]);
        if ($templateFile && file_exists($templateFile)) {
            $templateCode = file_get_contents($templateFile);
            // Remove <f:section name="Configuration">...</f:section> and <f:section name="Preview">...</f:section> from the template as they are not needed for the migration
            $templateCode = preg_replace('/<f:section name="Configuration">.*?<\/f:section>/s', '', $templateCode);
            $templateCode = preg_replace('/<f:section name="Preview">.*?<\/f:section>/s', '', $templateCode);
            // Replace <f:layout name="Content" /> with <f:layout name="Default" />
            $templateCode = preg_replace('/<f:layout name="Content"(.*?)>/', '<f:layout name="Flux"$1>', $templateCode);
            // Remove multiple \r\n or \n\n with a single \n
            $templateCode = preg_replace('/(\r\n|\n){3,}/', "\n\n", $templateCode);
            return $templateCode;
        }

        return null;
    }

    public function getBackendPreviewTemplate(string $contentType): ?string
    {
        $provider = null;
        foreach (Core::getRegisteredFlexFormProviders() as $registeredProvider) {
            if ($registeredProvider instanceof ProviderInterface && $registeredProvider->getContentObjectType() === $contentType) {
                $provider = $registeredProvider;
                break;
            }
        }

        $templateFile = $provider->getTemplatePathAndFilename([]);
        if ($templateFile && file_exists($templateFile)) {
            $templateCode = file_get_contents($templateFile);
            // Find the content of <f:section name="Preview">...</f:section> and return it as the backend preview template
            preg_match('/<f:section name="Preview">(.*?)<\/f:section>/s', $templateCode, $matches);
            if (isset($matches[1])) {
                $previewTemplate = $matches[1];
                // Remove multiple \r\n or \n\n with a single \n
                $previewTemplate = preg_replace('/(\r\n|\n){3,}/', "\n\n", $previewTemplate);
                return $previewTemplate;
            }
        }

        return null;
    }

    public function getIcon(string $contentType): ?string
    {
        $provider = null;
        foreach (Core::getRegisteredFlexFormProviders() as $registeredProvider) {
            if ($registeredProvider instanceof ProviderInterface && $registeredProvider->getContentObjectType() === $contentType) {
                $provider = $registeredProvider;
                break;
            }
        }

        $iconIdentifier = $provider->getForm([])->getOption(FormOption::ICON);
        if (!$iconIdentifier) {
            return null;
        }

        $source = $this->iconRegistry->getIconConfigurationByIdentifier($iconIdentifier)['options']['source'] ?? null;
        if (!$source) {
            return null;
        }

        return GeneralUtility::getFileAbsFileName($source);
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
