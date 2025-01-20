<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum AllocationValidationType: string implements HasLabel
{
    case NO_VALIDATION = 'no_validation';

    case HR = 'hr';

    case MANAGER = 'manager';

    case BOTH = 'both';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NO_VALIDATION => __('No Validation'),
            self::HR            => __('By Time Off Officer'),
            self::MANAGER       => __('By Employee\'s Approver'),
            self::BOTH          => __('By Employee\'s Approver and Time Off Officer'),
        };
    }

    public static function options(): array
    {
        return [
            self::NO_VALIDATION->value => __('No Validation'),
            self::HR->value            => __('By Time Off Officer'),
            self::MANAGER->value       => __('By Employee\'s Approver'),
            self::BOTH->value          => __('By Employee\'s Approver and Time Off Officer'),
        ];
    }
}
