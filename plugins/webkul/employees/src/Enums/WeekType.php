<?php

namespace Webkul\Employee\Enums;

enum WeekType: string
{
    case All = 'all';

    case Even = 'even';

    case Odd = 'odd';

    public static function options(): array
    {
        return [
            self::All->value  => __('employees::enums/week-type.all'),
            self::Even->value => __('employees::enums/week-type.even'),
            self::Odd->value  => __('employees::enums/week-type.odd'),
        ];
    }
}
