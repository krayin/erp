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
            self::Pending->value    => __('chatter::app.enums.task-status.pending'),
            self::InProgress->value => __('chatter::app.enums.task-status.in-progress'),
            self::Completed->value  => __('chatter::app.enums.task-status.completed'),
        ];
    }
}
