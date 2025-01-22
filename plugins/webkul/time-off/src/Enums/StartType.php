<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum StartType: string implements HasLabel
{
    case DAYS = 'days';

    case MONTHS = 'months';

    case YEARS = 'years';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DAYS   => __('time_off::enums/start-type.days'),
            self::MONTHS => __('time_off::enums/start-type.months'),
            self::YEARS  => __('time_off::enums/start-type.years'),
        };
    }

    public static function options(): array
    {
        return [
            self::MONTHS->value => __('time_off::enums/start-type.months'),
            self::DAYS->value   => __('time_off::enums/start-type.days'),
            self::YEARS->value  => __('time_off::enums/start-type.years'),
        ];
    }
}
