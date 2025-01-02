<?php

namespace Webkul\Support\Enums;

enum ActivityDecorationType: string
{
    case ALERT = 'alert';
    case ERROR = 'error';

    /**
     * Returns an array of options for dropdowns or selects.
     */
    public static function options(): array
    {
        return [
            self::ALERT->value => __('support::enums/activity-decoration-type.alert'),
            self::ERROR->value => __('support::enums/activity-decoration-type.error'),
        ];
    }
}
