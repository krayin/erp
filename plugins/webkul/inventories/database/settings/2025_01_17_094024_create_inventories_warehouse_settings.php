<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventories.warehouse.enable_locations', false);
        $this->migrator->add('inventories.warehouse.enable_multi_steps_routes', false);
    }
};
