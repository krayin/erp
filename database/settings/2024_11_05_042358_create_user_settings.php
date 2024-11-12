<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.enable_user_invitation', true);
        $this->migrator->add('general.enable_reset_password', true);
        $this->migrator->add('general.default_role', null);
    }
};
