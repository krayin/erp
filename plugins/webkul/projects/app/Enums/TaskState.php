<?php

namespace Webkul\Project\Enums;

enum TaskState: string
{
    case IN_PROGRESS = 'in_progress';
    case CHANGE_REQUESTED = 'change_requested';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';
    case DONE = 'done';
}
