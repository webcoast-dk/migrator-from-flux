<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Tree;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;
use WEBcoast\Migrator\Utility\TcaUtility;

class TreeFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|Tree $field, Field $normalizedField, array $config): void
    {
        $normalizedField->setType(FieldType::TREE);
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray([
                'allowNonIdValues' => $config['allowNonIdValues'] ?? null ?: null,
                'authMode' => $config['authMode'] ?? null ?: null,
                'behaviour' => [
                    'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
                ],
                'dbFieldLength' => $config['dbFieldLength'] ?? null ?: null,
                'default' => $field->getDefault() ?: null,
                'disableNoMatchingValueElement' => $config['disableNoMatchingValueElement'] ?? null ?: null,
                'exclusiveKeys' => $config['exclusiveKeys'] ?? null ?: null,
                'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
                'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
                'fileFolderConfig' => [
                    'allowedExtensions' => $config['fileFolderConfig']['allowedExtensions'] ?? null ?: null,
                    'depth' => $config['fileFolderConfig']['depth'] ?? null ?: null,
                    'folder' => $config['fileFolderConfig']['folder'] ?? null ?: null,
                ],
                'foreign_table' => $field->getTable(),
                'foreign_table_item_group' => $config['foreign_table_item_group'] ?? null ?: null,
                'foreign_table_prefix' => $config['foreign_table_prefix'] ?? null ?: null,
                'foreign_table_where' => $field->getCondition() ?: null,
                'items' => TcaUtility::migrateItemsFormat($field->getItems()),
                'itemsProcFunc' => $field->getItemsProcFunc() ?: null,
                'maxitems' => $field->getMaxItems(),
                'minitems' => $field->getMinItems(),
                'MM' => $field->getManyToMany() ?: null,
                'MM_match_fields' => $field->getMatchFields(),
                'MM_opposite_field' => $field->getOppositeField() ?: null,
                'MM_oppositeUsage' => $config['MM_oppositeUsage'] ?? null ?: null,
                'MM_table_where' => $config['MM_table_where'] ?? null ?: null,
                'MM_hasUidField' => $config['MM_hasUidField'] ?? null ?: null,
                'multiple' => $field->getMultiple() ?: null,
                'readOnly' => $config['readOnly'] ?? null ?: null,
                'size' => $field->getSize() ?: null,
                'treeConfig' => [
                    'dataProvider' => $config['treeConfig']['dataProvider'] ?? null ?: null,
                    'childrenField' => $config['treeConfig']['childrenField'] ?? null ?: null,
                    'parentField' => $field->getParentField() ?: null,
                    'startingPoints' => $config['treeConfig']['startingPoints'] ?? null ?: null,
                    'appearance' => [
                        'showHeader' => $field->getShowHeader() ?: null,
                        'expandAll' => $field->getExpandAll(),
                        'maxLevels' => $field->getMaxLevels(),
                        'nonSelectableLevels' => $field->getNonSelectableLevels(),
                    ]
                ]
            ])
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Tree;
    }
}
