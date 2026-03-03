<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\File;
use FluidTYPO3\Flux\Form\Field\MultiRelation;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

class LegacyFileFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|File $field, Field $normalizedField, array $config): void
    {
        $normalizedField->setType(FieldType::LEGACY_FILE);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
                'allowed' => $config['appearance']['elementBrowserAllowed'] ?? $field->getAllowed() ?? null,
                'maxitems' => $field->getMaxItems(),
                'minitems' => $field->getMinItems(),
                'appearance' => [
                    'showPossibleLocalizationRecords' => $config['behaviour']['allowLanguageSynchronization'] ?? null,
                    'showAllLocalizationLink' => $config['behaviour']['allowLanguageSynchronization'] ?? null,
                    'showSynchronizationLink' => $config['behaviour']['allowLanguageSynchronization'] ?? null,
                ],
                'readOnly' => $config['readOnly'] ?? null,
                'uploadFolder' => $config['uploadFolder'] ?? null,
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof File || ($field instanceof MultiRelation && $config['internal_type'] === 'file');
    }
}
