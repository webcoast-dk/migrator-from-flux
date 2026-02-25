<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Inline;
use FluidTYPO3\Flux\Form\Field\Inline\Fal;
use FluidTYPO3\Flux\Form\FieldInterface;
use WEBcoast\Migrator\Migration\FieldType;

class FileFieldNormalizer extends AbstractFieldConfigurationNormalizer
{

    public function normalize(FieldInterface|Fal|Inline $field, array $normalizedFieldConfiguration, array $config): array
    {
        $normalizedFieldConfiguration['type'] = FieldType::FILE;
        $normalizedFieldConfiguration['config'] = [
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
            $normalizedFieldConfiguration['config']['overrideChildTca']['columns']['uid_local']['config']['appearance']['elementBrowserAllowed'],
            $normalizedFieldConfiguration['config']['overrideChildTca']['columns']['uid_local']['config']['appearance']['elementBrowserType']
        );
        // Unset headerThumbnail if it's the default value to avoid unnecessary configuration
        if ($normalizedFieldConfiguration['config']['appearance']['headerThumbnail'] === ['field' => 'uid_local', 'width' => '64', 'height' => '64']) {
            unset($normalizedFieldConfiguration['config']['appearance']['headerThumbnail']);
        }

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        return $field instanceof Fal || ($field instanceof Inline && $field->getTable() === 'sys_file_reference');
    }
}
