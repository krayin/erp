<?php

namespace Webkul\Support\Settings;

use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public bool $enable_user_invitation;

    public bool $enable_reset_password;

    public ?int $default_role_id;

    public static function group(): string
    {
        return 'general';
    }
}
