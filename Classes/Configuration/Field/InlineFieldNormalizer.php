<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Inline;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\FieldType;

class InlineFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|Inline $field, array $normalizedFieldConfiguration, array $config): array
    {
        $normalizedFieldConfiguration['type'] = FieldType::INLINE;
        $normalizedFieldConfiguration['config'] = [
            'appearance' => [
                'collapseAll' => $field->getCollapseAll(),
                'expandSingle' => $field->getExpandSingle(),
                'showNewRecordLink' => $config['appearance']['showNewRecordLink'] ?? null ?: null,
                'newRecordLinkAddTitle' => $field->getNewRecordLinkAddTitle(),
                'newRecordLinkTitle' => $config['appearance']['newRecordLinkTitle'] ?? null ?: null,
                'levelLinksPosition' => $field->getLevelLinksPosition(),
                'useCombination' => $field->getUseCombination(),
                'suppressCombinationWarning' => $config['appearance']['suppressCombinationWarning'] ?? null ?: null,
                'useSortable' => $field->getUseSortable(),
                'showPossibleLocalizationRecords' => $field->getShowPossibleLocalizationRecords(),
                'showAllLocalizationLink' => $field->getShowAllLocalizationLink(),
                'showSynchronizationLink' => $field->getShowSynchronizationLink(),
                'enabledControls' => $field->getEnabledControls(),
                'showPossibleRecordSelector' => $config['appearance']['showPossibleRecordSelector'] ?? null ?: null,
                'elementBrowserEnabled' => $config['appearance']['elementBrowserEnabled'] ?? null ?: null,
            ],
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
                'disableMovingChildrenWithParent' => $field->getDisableMovingChildrenWithParent(),
                'enableCascadingDelete' => $config['behaviour']['enableCascadingDelete'] ?? null ?: null,
            ],
            'customControls' => $config['customControls'] ?? null ?: null,
            'filter' => $config['filter'] ?? null ?: null,
            'foreign_default_sortby' => $field->getForeignDefaultSortby() ?: null,
            'foreign_field' => $field->getForeignField() ?: null,
            'foreign_label' => $field->getForeignLabel() ?: null,
            'foreign_match_fields' => $field->getForeignMatchFields() ?: null,
            'foreign_selector' => $field->getForeignSelector() ?: null,
            'foreign_sortby' => $field->getForeignSortby() ?: null,
            'foreign_table' => $field->getTable(),
            'foreign_table_where' => $field->getCondition() ?: null,
            'foreign_table_field' => $field->getForeignTableField() ?: null,
            'foreign_unique' => $field->getForeignUnique() ?: null,
            'maxitems' => $field->getMaxItems(),
            'minitems' => $field->getMinItems(),
            'MM' => $field->getManyToMany() ?: null,
            'MM_opposite_field' => $field->getOppositeField() ?: null,
            'MM_hasUidField' => $config['MM_hasUidField'] ?? null ?: null,
            'overrideChildTca' => $field->getOverrideChildTca() ?: null,
            'size' => $field->getSize() ?: null,
            'symmetric_field' => $field->getSymmetricField() ?: null,
            'symmetric_label' => $field->getSymmetricLabel() ?: null,
            'symmetric_sortby' => $field->getSymmetricSortby() ?: null,
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Inline && $field->getTable() !== 'sys_file_reference';
    }
}
