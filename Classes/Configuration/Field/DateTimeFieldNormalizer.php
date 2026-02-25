<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\DateTime;
use FluidTYPO3\Flux\Form\Field\Input;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\FieldType;

class DateTimeFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|DateTime|Input $field, array $normalizedFieldConfiguration, array $config): array
    {
        $normalizedField['type'] = FieldType::DATETIME;
        $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);
        $format = null;
        foreach (['date', 'datetime', 'time', 'timesec'] as $possibleFormat) {
            if (in_array($possibleFormat, $eval, true)) {
                $format = $possibleFormat;
                break;
            }
        }
        $normalizedField['config'] = [
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'dbType' => $config['dbType'] ?? null ?: null,
            'default' => $field->getDefault() ?: null,
            'disableAgeDisplay' => $config['disableAgeDisplay'] ?? null ?: null,
            'fieldControl' => $config['fieldControl'] ?? null ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'format' => $format,
            'mode' => $config['mode'] ?? null ?: null,
            'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
            'placeholder' => $field->getPlaceholder() ?: null,
            'range' => [
                'lower' => $field->getMinimum() ?: null,
                'upper' => $field->getMaximum() ?: null,
            ],
            'readOnly' => $config['readOnly'] ?? null ?: null,
            'search' => $config['search'] ?? null ?: null,
            'softref' => $config['softref'] ?? null ?: null,
        ];

        return $normalizedField;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        if ($field instanceof DateTime) {
            return true;
        }

        return $field instanceof Input && $config['renderType'] === 'inputDateTime';
    }
}
