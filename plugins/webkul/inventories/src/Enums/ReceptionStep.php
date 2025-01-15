<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum ReceptionStep: string implements HasDescription, HasLabel
{
    case ONE_STEP = 'one_step';

    case TWO_STEPS = 'two_steps';

    case THREE_STEPS = 'three_steps';

    public function getLabel(): string
    {
        return match ($this) {
            self::ONE_STEP       => __('inventories::enums/reception-step.one-step.name'),
            self::TWO_STEPS      => __('inventories::enums/reception-step.two-steps.name'),
            self::THREE_STEPS    => __('inventories::enums/reception-step.three-steps.name'),
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::ONE_STEP       => __('inventories::enums/reception-step.one-step.description'),
            self::TWO_STEPS      => __('inventories::enums/reception-step.two-steps.description'),
            self::THREE_STEPS    => __('inventories::enums/reception-step.three-steps.description'),
        };
    }
}
