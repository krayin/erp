<?php

namespace Webkul\Project\Settings;

use Spatie\LaravelSettings\Settings;

class TimeSettings extends Settings
{
    public bool $enable_timesheets;

    public static function group(): string
    {
        return 'time';
    }
}
