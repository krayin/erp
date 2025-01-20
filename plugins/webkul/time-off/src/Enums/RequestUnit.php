<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum RequestUnit: string implements HasLabel
{
    case DAY = 'day';

    case HALF_DAY = 'half_day';

    case HOUR = 'hour';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DAY      => __('Day'),
            self::HALF_DAY => __('Half Day'),
            self::HOUR     => __('Hour'),
        };
    }

    public static function options(): array
    {
        return [
            self::DAY->value      => __('Day'),
            self::HALF_DAY->value => __('Half Day'),
            self::HOUR->value     => __('Hour'),
        ];
    }
}
