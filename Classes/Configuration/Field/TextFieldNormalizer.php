<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Input;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

class TextFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, Field $normalizedField, array $config): void
    {
        $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);
        $normalizedField->setType(FieldType::TEXT);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
                'behaviour' => [
                    'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
                ],
                'autocomplete' => $config['autocomplete'] ?? null ?: null,
                'default' => $field->getDefault() ?: null,
                'eval' => $config['eval'] ?? null ?: null,
                'fieldControl' => $config['fieldControl'] ?? null ?: null,
                'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
                'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
                'is_in' => $config['is_in'] ?? null ?: null,
                'max' => $field->getMaxCharacters() ?: null,
                'min' => $config['min'] ?? null,
                'mode' => $config['mode'] ?? null ?: null,
                'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
                'placeholder' => $field->getPlaceholder() ?: null,
                'readOnly' => $config['readOnly'] ?? null ?: null,
                'required' => $field->getRequired() ?: null,
                'search' => $config['search'] ?? null ?: null,
                'size' => $field->getSize() ?: null,
                'softref' => $config['softref'] ?? null ?: null,
                'valuePicker' => $config['valuePicker'] ?? null ?: null,
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        if ($field instanceof Input && ($config['renderType'] ?? '') === '') {
            $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);

            return !in_array('email', $eval)
                && !in_array('int', $eval)
                && !in_array('double2', $eval)
                && !in_array('password', $eval)
                && !in_array('saltedPassword', $eval);
        }

        return false;
    }
}
