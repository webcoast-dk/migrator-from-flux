<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\Field\Input;
use FluidTYPO3\Flux\Form\FieldInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\Migrator\Migration\FieldType;

class EmailFieldNormalizer extends AbstractFieldConfigurationNormalizer
{
    public function normalize(FieldInterface $field, array $normalizedFieldConfiguration, array $config): array
    {
        $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);
        $eval = ArrayUtility::removeArrayEntryByValue($eval, 'email');
        $normalizedFieldConfiguration['type'] = FieldType::EMAIL;
        $normalizedFieldConfiguration['config'] = [
            'behaviour' => [
                'allowLanguageSynchronization' => $config['behaviour']['allowLanguageSynchronization'] ?? null ?: null,
            ],
            'autocomplete' => $config['autocomplete'] ?? null ?: null,
            'eval' => implode(',', $eval) ?: null,
            'fieldInformation' => $config['fieldInformation'] ?? null ?: null,
            'fieldWizard' => $config['fieldWizard'] ?? null ?: null,
            'mode' => $config['mode'] ?? null ?: null,
            'nullable' => $config['nullable'] ?? null ?: in_array('null', $eval) ?: null,
            'placeholder' => $field->getPlaceholder() ?: null,
            'readOnly' => $config['readOnly'] ?? null ?: null,
            'required' => $field->getRequired() ?: null,
            'size' => $field->getSize() ?: null,
        ];

        return $normalizedFieldConfiguration;
    }

    public function supports(FieldInterface $field, array $config): bool
    {
        if ($field instanceof Input) {
            $eval = GeneralUtility::trimExplode(',', $field->getValidate() ?? '', true);
            return in_array('email', $eval);
        }

        return false;
    }
}
