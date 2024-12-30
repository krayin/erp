<?php

namespace Webkul\Employee\Enums;

enum DayPeriod: string
{
    case Morning = 'morning';

    case Afternoon = 'afternoon';

    case Evening = 'evening';

    case Night = 'night';

    public static function options(): array
    {
        return [
            self::Morning->value   => __('employees::enums/day-period.morning'),
            self::Afternoon->value => __('employees::enums/day-period.afternoon'),
            self::Evening->value   => __('employees::enums/day-period.evening'),
            self::Night->value     => __('employees::enums/day-period.night'),
        ];
    }
}
