<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use TYPO3\CMS\Core\Localization\LanguageService;

abstract class AbstractFieldConfigurationNormalizer implements FieldConfigurationNormalizerInterface
{
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
