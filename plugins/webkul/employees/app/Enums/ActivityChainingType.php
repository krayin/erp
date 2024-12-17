<?php

namespace Webkul\Employee\Enums;

enum ActivityChainingType: string
{
    case SUGGEST = 'suggest';

    case TRIGGER = 'trigger';

    public static function options(): array
    {
        return [
            self::SUGGEST->value => 'Suggest Next Activity',
            self::TRIGGER->value => 'Trigger Next Activity',
        ];
    }
}
