<?php

namespace Webkul\Employee\Enums;

enum DayOfWeek: string
{
    case Monday = 'monday';

    case Tuesday = 'tuesday';

    case Wednesday = 'wednesday';

    case Thursday = 'thursday';

    case Friday = 'friday';

    case Saturday = 'saturday';

    case Sunday = 'sunday';

    public static function options(): array
    {
        return [
            self::Monday->value     => 'Monday',
            self::Tuesday->value    => 'Tuesday',
            self::Wednesday->value  => 'Wednesday',
            self::Thursday->value   => 'Thursday',
            self::Friday->value     => 'Friday',
            self::Saturday->value   => 'Saturday',
            self::Sunday->value     => 'Sunday',
        ];
    }
}
