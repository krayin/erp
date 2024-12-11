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
            self::All->value       => 'All',
            self::Even->value      => 'Even',
            self::Odd->value       => 'Odd',
        ];
    }
}
