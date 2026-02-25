<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\FieldInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('webcoast.migrator_from_flux.field_configuration_normalizer')]
interface FieldConfigurationNormalizerInterface
{
    public function normalize(FieldInterface $field, array $normalizedFieldConfiguration, array $config): array;

    public function supports(FieldInterface $field, array $config): bool;
}
