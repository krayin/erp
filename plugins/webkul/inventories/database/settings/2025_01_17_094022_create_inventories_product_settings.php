<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventories_product.enable_variants', true);
        $this->migrator->add('inventories_product.enable_uom', false);
        $this->migrator->add('inventories_product.enable_packagings', false);
    }
};
