<?php

namespace Webkul\Support\Enums;

enum ActivityChainingType: string
{
    case SUGGEST = 'suggest';

    case TRIGGER = 'trigger';

    public static function options(): array
    {
        return [
            self::SUGGEST->value => __('support::enums/activity-chaining-type.suggest'),
            self::TRIGGER->value => __('support::enums/activity-chaining-type.trigger'),
        ];
    }
}
