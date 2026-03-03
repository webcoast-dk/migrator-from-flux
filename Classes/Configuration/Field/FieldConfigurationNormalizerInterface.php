<?php

declare(strict_types=1);

namespace WEBcoast\MigratorFromFlux\Configuration\Field;

use FluidTYPO3\Flux\Form\FieldInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use WEBcoast\Migrator\Migration\Field;

#[AutoconfigureTag('webcoast.migrator_from_flux.field_configuration_normalizer')]
interface FieldConfigurationNormalizerInterface
{
    public function normalize(FieldInterface $field, Field $normalizedField, array $config): void;

    public function supports(FieldInterface $field, array $config): bool;
}
