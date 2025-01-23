<?php

namespace Webkul\Support\Enums;

enum Week: string
{
    case SUNDAY = 'sunday';

    case MONDAY = 'monday';

    case TUESDAY = 'tuesday';

    case WEDNESDAY = 'wednesday';

    case THURSDAY = 'thursday';

    case FRIDAY = 'friday';

    case SATURDAY = 'saturday';

    public static function options(): array
    {
        return [
            self::SUNDAY->value    => __('Sunday'),
            self::MONDAY->value    => __('Monday'),
            self::TUESDAY->value   => __('Tuesday'),
            self::WEDNESDAY->value => __('Wednesday'),
            self::THURSDAY->value  => __('Thursday'),
            self::FRIDAY->value    => __('Friday'),
            self::SATURDAY->value  => __('Saturday'),
        ];
    }
}
