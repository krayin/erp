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
                '2025_01_06_072130_create_warehouses_warehouses_table',
                '2025_01_06_072135_create_warehouses_storage_categories_table',
                '2025_01_06_072224_create_warehouses_locations_table',
                '2025_01_06_072349_create_warehouses_picking_types_table',
                '2025_01_06_072353_create_warehouses_routes_table',
                '2025_01_06_072356_create_warehouses_rules_table',
                '2025_01_06_143103_create_warehouses_route_warehouses_table',
                '2025_01_07_083342_add_relationship_to_warehouses_warehouses_table',
                '2025_01_07_095737_create_warehouses_warehouse_resupplies_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
