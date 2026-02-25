<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Input;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\FieldType;

class ColorPickerFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|Input $field, array $normalizedFieldConfiguration, array $config): array
    {
        $eval = GeneralUtility::trimExplode(',', $config['eval'] ?? '', true);
        $normalizedFieldConfiguration['type'] = FieldType::COLOR;
        $normalizedFieldConfiguration['config'] = [
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'default' => $field->getDefault() ?: null,
            'fieldControl' => $config['fieldControl'] ?? null ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'mode' => $config['mode'] ?? null ?: null,
            'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
            'placeholder' => $field->getPlaceholder() ?: null,
            'readOnly' => $config['readOnly'] ?? null ?: null,
            'required' => $field->getRequired() ?: null,
            'size' => $field->getSize() ?: null,
            'valuePicker' => $config['valuePicker'] ?? null ?: null,
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Input && $config['renderType'] === 'colorpicker';
    }
}
