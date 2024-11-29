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
            self::Todo->value => 'To Do',
            self::Email->value => 'Email',
            self::Call->value => 'Call',
            self::Meeting->value => 'Meeting',
        ];
    }
}
