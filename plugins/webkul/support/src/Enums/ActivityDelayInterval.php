<?php

namespace Webkul\Support\Enums;

enum ActivityDelayInterval: string
{
    case BEFORE_PLAN_DATE = 'before_plan_date';
    case AFTER_PLAN_DATE = 'after_plan_date';

    /**
     * Returns an array of options for dropdowns or selects.
     */
    public static function options(): array
    {
        return [
            self::BEFORE_PLAN_DATE->value => __('support::enums/activity-delay-interval.before-plan-date'),
            self::AFTER_PLAN_DATE->value  => __('support::enums/activity-delay-interval.after-plan-date'),
        ];
    }
}
