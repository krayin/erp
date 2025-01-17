<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventories.product.enable_variants', false);
        $this->migrator->add('inventories.product.enable_uom', false);
        $this->migrator->add('inventories.product.enable_packagings', false);
    }
};
