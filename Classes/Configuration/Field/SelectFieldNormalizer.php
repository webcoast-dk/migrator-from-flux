<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Relation;
use FluidTYPO3\Flux\Form\Field\Select;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\TcaUtility;

class SelectFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, array $normalizedFieldConfiguration, array $config): array
    {
        $normalizedFieldConfiguration['type'] = FieldType::SELECT;
        $normalizedFieldConfiguration['config'] = [
            'renderType' => $config['renderType'] ?? null ?: 'selectSingle',
            'allowNonIdValues' => $config['allowNonIdValues'] ?? null ?: null,
            'appearance' => [
                'selectCheckBox' => $config['appearance']['selectCheckBox'] ?? null ?: null,
            ],
            'authMode' => $config['authMode'] ?? null ?: null,
            'autoSizeMax' => $config['autoSizeMax'] ?? null ?: null,
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'default' => $config['default'] ?? null,
            'disableNoMatchingValueElement' => $config['disableNoMatchingValueElement'] ?? null ?: null,
            'dontRemapTablesOnCopy' => $config['dontRemapTablesOnCopy'] ?? null ?: null,
            'exclusiveKeys' => $config['exclusiveKeys'] ?? null ?: null,
            'fieldControl' => $config['fieldControl'] ?? null ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'fileFolderConfig' => [
                'allowedExtensions' => $config['fileFolderConfig']['allowedExtensions'] ?? null ?: null,
                'depth' => $config['fileFolderConfig']['depth'] ?? null ?: null,
                'folder' => $config['fileFolderConfig']['folder'] ?? null ?: null,
            ],
            'foreign_table' => $config['foreign_table'] ?? null ?: null,
            'foreign_table_item_group' => $config['foreign_table_item_group'] ?? null ?: null,
            'foreign_table_prefix' => $config['foreign_table_prefix'] ?? null ?: null,
            'foreign_table_where' => $config['foreign_table_where'] ?? null ?: null,
            'itemGroups' => $config['itemGroups'] ?? null ?: null,
            'items' => TcaUtility::migrateItemsFormat($config['items'] ?? null ?: []),
            'itemsProcFunc' => $config['itemsProcFunc'] ?? null ?: null,
            'localizeReferencesAtParentLocalization' => $config['localizeReferencesAtParentLocalization'] ?? null ?: null,
            'maxitems' => $config['maxitems'] ?? null ?: null,
            'minitems' => $config['minitems'] ?? null ?: null,
            'MM' => $config['MM'] ?? null ?: null,
            'MM_match_fields' => $config['MM_match_fields'] ?? null ?: null,
            'MM_opposite_field' => $config['MM_opposite_field'] ?? null ?: null,
            'MM_oppositeUsage' => $config['MM_oppositeUsage'] ?? null ?: null,
            'MM_table_where' => $config['MM_table_where'] ?? null ?: null,
            'MM_hasUidField' => $config['MM_hasUidField'] ?? null ?: null,
            'multiple' => $config['multiple'] ?? null ?: null,
            'multiSelectFilterItems' => $config['multiSelectFilterItems'] ?? null ?: null,
            'readOnly' => $config['readOnly'] ?? null ?: null,
            'size' => $config['size'] ?? null ?: null,
            'sortItems' => $config['sortItems'] ?? null ?: null,
            'treeConfig' => [
                'dataProvider' => $config['treeConfig']['dataProvider'] ?? null ?: null,
                'childrenField' => $config['treeConfig']['childrenField'] ?? null ?: null,
                'parentField' => $config['treeConfig']['parentField'] ?? null ?: null,
                'startingPoints' => $config['treeConfig']['startingPoints'] ?? null ?: null,
                'appearance' => [
                    'showHeader' => $config['treeConfig']['appearance']['showHeader'] ?? null ?: null,
                    'expandAll' => $config['treeConfig']['appearance']['expandAll'] ?? null ?: null,
                    'maxLevels' => $config['treeConfig']['appearance']['maxLevels'] ?? null ?: null,
                    'nonSelectableLevels' => $config['treeConfig']['appearance']['nonSelectableLevels'] ?? null ?: null,
                ]
            ]
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Select || $field instanceof Relation;
    }
}
