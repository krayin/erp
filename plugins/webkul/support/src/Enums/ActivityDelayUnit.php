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
            self::MINUTES->value => __('support::enums/activity-delay-unit.minutes'),
            self::HOURS->value   => __('support::enums/activity-delay-unit.hours'),
            self::DAYS->value    => __('support::enums/activity-delay-unit.days'),
            self::WEEKS->value   => __('support::enums/activity-delay-unit.weeks'),
        ];
    }
}
