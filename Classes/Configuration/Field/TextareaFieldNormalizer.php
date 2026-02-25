<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Text;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\FieldType;

class TextareaFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, array $normalizedFieldConfiguration, array $config): array
    {
        $eval = GeneralUtility::trimExplode(',', $config['eval'] ?? '', true);
        $normalizedFieldConfiguration['type'] = FieldType::TEXTAREA;
        $normalizedFieldConfiguration['config'] = [
            'renderType' => $config['renderType'] ?? null ?: null,
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'cols' => $config['cols'] ?? null ?: null,
            'default' => $config['default'] ?? null ?: null,
            'enableRichtext' => $config['enableRichtext'] ?? null ?: null,
            'enableTabulator' => $config['enableTabulator'] ?? null ?: null,
            'eval' => $config['eval'] ?? null ?: null,
            'fieldControl' => $config['fieldControl'] ?? null ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'fixedFont' => $config['fixedFont'] ?? null ?: null,
            'format' => $config['format'] ?? null ?: null,
            'is_in' => $config['is_in'] ?? null ?: null,
            'max' => $config['max'] ?? null ?: null,
            'min' => $config['min'] ?? null ?: null,
            'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
            'placeholder' => $field->getPlaceholder() ?: null,
            'readOnly' => $config['readOnly'] ?? null ?: null,
            'required' => $field->getRequired() ?: null,
            'richtextConfiguration' => $config['richtextConfiguration'] ?? null ?: null,
            'rows' => $config['rows'] ?? null ?: null,
            'search' => $config['search'] ?? null ?: null,
            'softref' => $config['softref'] ?? null ?: null,
            'wrap' => $config['wrap'] ?? null ?: null,
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Text;
    }
}
