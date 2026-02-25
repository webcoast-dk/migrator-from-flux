<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Input;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\FieldType;

class LinkFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|Input $field, array $normalizedFieldConfiguration, array $config): array
    {
        $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);
        $normalizedFieldConfiguration['type'] = FieldType::LINK;
        $normalizedFieldConfiguration['config'] = [
            'allowedTypes' => $config['allowedTypes'] ?? null ?: null,
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'appearance' => [
                'allowedOptions' => $config['appearance']['allowedOptions'] ?? null ?: null,
                'allowedFilesExtensions' => $config['appearance']['allowedFilesExtensions'] ?? null ?: null,
                'browserTitle' => $config['appearance']['browserTitle'] ?? null ?: null,
                'enableBrowser' => $config['appearance']['enableBrowser'] ?? null ?: null,
            ],
            'autocomplete' => $config['autocomplete'] ?? null ?: null,
            'default' => $field->getDefault() ?: null,
            'fieldControl' => $config['fieldControl'] ?? null ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'mode' => $config['mode'] ?? null ?: null,
            'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
            'placeholder' => $field->getPlaceholder() ?: null,
            'readOnly' => $config['readOnly'] ?? null ?: null,
            'required' => $field->getRequired() ?: null,
            'search' => $config['search'] ?? null ?: null,
            'size' => $field->getSize() ?: null,
            'valuePicker' => $config['valuePicker'] ?? null ?: null,
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Input && $config['renderType'] === 'inputLink';
    }
}
