<?php

namespace Webkul\Support\Enums;

enum ActivityDelayUnit: string
{
    case MINUTES = 'minutes';
    case HOURS = 'hours';
    case DAYS = 'days';
    case WEEKS = 'weeks';

    /**
     * Returns an array of options for dropdowns or selects.
     */
    public static function options(): array
    {
        return [
            self::MINUTES->value => 'Minutes',
            self::HOURS->value   => 'Hours',
            self::DAYS->value    => 'Days',
            self::WEEKS->value   => 'Weeks',
        ];
    }
}
