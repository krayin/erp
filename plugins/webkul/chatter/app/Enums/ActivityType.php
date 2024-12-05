<?php

namespace Webkul\Chatter\Enums;

enum ActivityType: string
{
    case Todo = 'todo';

    case Email = 'email';

    case Call = 'call';

    case Meeting = 'meeting';

    public static function options(): array
    {
        return [
            self::Todo->value    => __('chatter::app.enums.activity-type.to-do'),
            self::Email->value   => __('chatter::app.enums.activity-type.email'),
            self::Call->value    => __('chatter::app.enums.activity-type.call'),
            self::Meeting->value => __('chatter::app.enums.activity-type.meeting'),
        ];
    }
}
