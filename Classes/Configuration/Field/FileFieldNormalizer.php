<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Inline;
use FluidTYPO3\Flux\Form\Field\Inline\Fal;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\Field;
use WEBcoast\Migrator\Migration\FieldType;
use WEBcoast\Migrator\Utility\ArrayUtility;

class FileFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface|Fal|Inline $field, Field $normalizedField, array $config): void
    {
        $normalizedField->setType(FieldType::FILE);
        $normalizedConfiguration = [
            'allowed' => $field->getOverrideChildTca()['columns']['uid_local']['config']['appearance']['elementBrowserAllowed'] ?? null,
            'appearance' => [
                'collapseAll' => $field->getCollapseAll(),
                'expandSingle' => $field->getExpandSingle(),
                'createNewRelationLinkTitle' => $this->getLanguageService()->sL($field->getNewRecordLinkAddTitle()),
                'useSortable' => $field->getUseSortable(),
                'showPossibleLocalizationRecords' => $field->getShowPossibleLocalizationRecords(),
                'showAllLocalizationLink' => $field->getShowAllLocalizationLink(),
                'showSynchronizationLink' => $field->getShowSynchronizationLink(),
                'enabledControls' => $field->getEnabledControls(),
                'headerThumbnail' => $field->getHeaderThumbnail(),
                'fileUploadAllowed' => $config['appearance']['fileUploadAllowed'] ?? null,
                'fileByUrlAllowed' => $config['appearance']['fileByUrlAllowed'] ?? null,
                'elementBrowserEnabled' => $config['appearance']['elementBrowserEnabled'] ?? null,
            ],
            'behaviour' => [
                'localizationMode' => $field->getLocalizationMode(),
                'disableMovingChildrenWithParent' => $field->getDisableMovingChildrenWithParent(),
            ],
            'maxitems' => $field->getMaxItems(),
            'minitems' => $field->getMinItems(),
            'overrideChildTca' => $field->getOverrideChildTca(),
        ];
        // Unset allowed from overrideChildTca as it's already set on the field configuration
        unset(
            $normalizedConfiguration['overrideChildTca']['columns']['uid_local']['config']['appearance']['elementBrowserAllowed'],
            $normalizedConfiguration['overrideChildTca']['columns']['uid_local']['config']['appearance']['elementBrowserType']
        );
        // Unset headerThumbnail if it's the default value to avoid unnecessary configuration
        if ($normalizedConfiguration['appearance']['headerThumbnail'] === ['field' => 'uid_local', 'width' => '64', 'height' => '64']) {
            unset($normalizedConfiguration['appearance']['headerThumbnail']);
        }
        $normalizedField->setConfiguration(
            ArrayUtility::removeEmptyValuesFromArray(
                $normalizedConfiguration
            )
        );
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Fal || ($field instanceof Inline && $field->getTable() === 'sys_file_reference');
    }
}
