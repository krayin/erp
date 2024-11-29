<?php

namespace Webkul\Chatter\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';

    case InProgress = 'in_progress';

    case Completed = 'completed';

    public static function options(): array
    {
        return [
            self::Pending->value => 'Pending',
            self::InProgress->value => 'In Progress',
            self::Completed->value => 'Completed',
        ];
    }
}
