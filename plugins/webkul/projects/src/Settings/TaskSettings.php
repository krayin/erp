<?php

namespace Webkul\Project\Settings;

use Spatie\LaravelSettings\Settings;

class TaskSettings extends Settings
{
    public bool $enable_recurring_tasks;

    public bool $enable_task_dependencies;

    public bool $enable_project_stages;

    public bool $enable_milestones;

    public static function group(): string
    {
        return 'task';
    }
}
