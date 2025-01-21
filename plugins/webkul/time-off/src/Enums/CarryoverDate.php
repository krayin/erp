<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum CarryoverDate: string implements HasLabel
{
    case YEAR_START = 'year_start';

    case ALLOCATION = 'allocation';

    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::YEAR_START => __('At the start of the year'),
            self::ALLOCATION => __('At the allocation date'),
            self::OTHER      => __('Other'),
        };
    }

    public static function options(): array
    {
        return [
            self::YEAR_START->value => __('At the start of the year'),
            self::ALLOCATION->value => __('At the allocation date'),
            self::OTHER->value      => __('Other'),
        ];
    }
}
