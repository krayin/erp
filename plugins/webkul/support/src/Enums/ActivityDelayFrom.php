<?php

namespace Webkul\Support\Enums;

enum ActivityDelayFrom: string
{
    case PREVIOUS_ACTIVITY = 'previous_activity';
    case CURRENT_DATE = 'current_date';

    /**
     * Returns an array of options for dropdowns or selects.
     */
    public static function options(): array
    {
        return [
            self::PREVIOUS_ACTIVITY->value => __('support::enums/activity-delay-from.previous-activity'),
            self::CURRENT_DATE->value      => __('support::enums/activity-delay-from.current-date'),
        ];
    }
}
