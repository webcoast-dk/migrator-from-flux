<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\MultiRelation;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

class GroupFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, Field $normalizedField, array $config): void
    {
        $normalizedField->setType(FieldType::GROUP);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
                'behaviour' => [
                    'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
                ],
                'autoSizeMax' => $config['autoSizeMax'] ?? null ?: null,
                'elementBrowserEntryPoints' => $config['elementBrowserEntryPoints'] ?? null ?: null,
                'fieldControl' => $config['fieldControl'] ?? null ?: null,
                'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
                'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
                'hideDeleteIcon' => $config['hideDeleteIcon'] ?? null ?: null,
                'hideMoveIcons' => $config['hideMoveIcons'] ?? null ?: null,
                'internal_type' => $config['internal_type'] ?? null ?: null,
                'maxitems' => $config['maxitems'] ?? null ?: null,
                'minitems' => $config['minitems'] ?? null ?: null,
                'multiple' => $config['multiple'] ?? null ?: null,
                'readOnly' => $config['readOnly'] ?? null ?: null,
                'size' => $config['size'] ?? null ?: null,
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof MultiRelation && $config['internal_type'] === 'db';
    }
}
