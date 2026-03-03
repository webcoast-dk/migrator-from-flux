<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Text;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

class TextareaFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, Field $normalizedField, array $config): void
    {
        $eval = GeneralUtility::trimExplode(',', $config['eval'] ?? '', true);
        $normalizedField->setType(FieldType::TEXTAREA);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
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
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Text;
    }
}
