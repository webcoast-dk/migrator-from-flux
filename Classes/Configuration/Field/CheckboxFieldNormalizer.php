<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Checkbox;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;
use WEBcoast\Migrator\Utility\TcaUtility;

class CheckboxFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|Checkbox $field, Field $normalizedField, array $config): void
    {
        $normalizedField->setType(FieldType::CHECKBOX);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
                'behaviour' => [
                    'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
                ],
                'cols' => $config['cols'] ?? null ?: null,
                'default' => $field->getDefault() ?: null,
                'eval' => $config['eval'] ?? null ?: null,
                'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
                'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
                'invertStateDisplay' => $config['invertStateDisplay'] ?? null ?: null,
                'items' => TcaUtility::migrateItemsFormat($config['items'] ?? null ?: []),
                'itemsProcFunc' => $config['itemsProcFunc'] ?? null ?: null,
                'readOnly' => $config['readOnly'] ?? null ?: null,
                'renderType' => $config['renderType'] ?? null ?: null,
                'validation' => $config['validation'] ?? null ?: null,
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Checkbox;
    }
}
