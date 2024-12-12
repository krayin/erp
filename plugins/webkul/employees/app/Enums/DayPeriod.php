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
            self::Morning->value   => 'Morning',
            self::Afternoon->value => 'Afternoon',
            self::Evening->value   => 'Evening',
            self::Night->value     => 'Night',
        ];
    }
}
