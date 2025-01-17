<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventories.traceability.enable_lost_serial_numbers', false);
        $this->migrator->add('inventories.traceability.enable_expiration_dates', false);
        $this->migrator->add('inventories.traceability.display_on_delivery_slips', false);
        $this->migrator->add('inventories.traceability.enable_consignments', false);
    }
};
