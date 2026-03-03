<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Input;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

class NumberFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, Field $normalizedField, array $config): void
    {
        $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);
        $normalizedField->setType(FieldType::NUMBER);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
                'behaviour' => [
                    'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
                ],
                'autocomplete' => $config['autocomplete'] ?? null ?: null,
                'default' => $field->getDefault() ?: null,
                'fieldControl' => $config['fieldControl'] ?? null ?: null,
                'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
                'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
                'format' => in_array('int', $eval) ? null : 'decimal',
                'mode' => $config['mode'] ?? null ?: null,
                'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
                'range' => [
                    'lower' => $field->getMinimum() ?: null,
                    'upper' => $field->getMaximum() ?: null,
                ],
                'readOnly' => $config['readOnly'] ?? null ?: null,
                'required' => $field->getRequired() ?: null,
                'size' => $field->getSize() ?: null,
                'slider' => $config['slider'] ?? null ?: null,
                'valuePicker' => $config['valuePicker'] ?? null ?: null,
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        if ($field instanceof Input) {
            $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);

            return in_array('int', $eval, true) || in_array('double2', $eval, true);
        }

        return false;
    }
}
