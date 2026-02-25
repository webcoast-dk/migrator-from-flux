<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Radio;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\TcaUtility;

class RadiobuttonFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, array $normalizedFieldConfiguration, array $config): array
    {
        $normalizedFieldConfiguration['type'] = FieldType::RADIO;
        $normalizedFieldConfiguration['config'] = [
            'behavior' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'default' => $config['default'] ?? null ?: null,
            'fieldControl' => $config['fieldControl'] ?? null ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'items' => TcaUtility::migrateItemsFormat($config['items'] ?? null ?: []),
            'itemsProcFunc' => $config['itemsProcFunc'] ?? null ?: null,
            'readOnly' => $config['readOnly'] ?? null ?: null,
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Radio;
    }
}
