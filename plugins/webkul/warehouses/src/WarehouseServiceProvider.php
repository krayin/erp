<?php

namespace Webkul\Warehouse;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class WarehouseServiceProvider extends PackageServiceProvider
{
    public static string $name = 'warehouses';

    public static string $viewNamespace = 'warehouses';

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
