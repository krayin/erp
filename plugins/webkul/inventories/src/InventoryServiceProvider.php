<?php

namespace Webkul\Inventory;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class InventoryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'inventories';

    public static string $viewNamespace = 'inventories';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
