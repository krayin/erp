<?php

namespace Webkul\Inventory;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
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
                '2025_01_06_072032_create_inventories_tags_table',
                '2025_01_06_072130_create_inventories_warehouses_table',
                '2025_01_06_072135_create_inventories_storage_categories_table',
                '2025_01_06_072224_create_inventories_locations_table',
                '2025_01_06_072349_create_inventories_operation_types_table',
                '2025_01_06_072353_create_inventories_routes_table',
                '2025_01_06_072356_create_inventories_rules_table',
                '2025_01_06_143103_create_inventories_route_warehouses_table',
                '2025_01_07_083342_add_relationship_to_inventories_warehouses_table',
                '2025_01_07_095737_create_inventories_warehouse_resupplies_table',
                '2025_01_07_145741_create_inventories_package_types_table',
                '2025_01_07_145741_create_inventories_packages_table',
                '2025_01_10_091035_alter_products_products_table',
                '2025_01_10_095946_create_inventories_category_routes_table',
                '2025_01_10_095946_create_inventories_product_routes_table',
                '2025_01_10_102716_add_package_type_id_column_in_products_packagings_table',
                '2025_01_10_111734_create_inventories_storage_category_capacities_table',
                '2025_01_13_061029_create_inventories_route_packagings_table',
                '2025_01_14_092601_create_inventories_lots_table',
                '2025_01_14_113233_create_inventories_product_quantities_table',
                '2025_01_14_113235_create_inventories_product_quantity_relocations_table',
                '2025_01_14_133233_create_inventories_operations_table',
                '2025_01_14_133245_create_inventories_package_levels_table',
                '2025_01_14_133246_create_inventories_package_destinations_table',
                '2025_01_14_133250_create_inventories_scraps_table',
                '2025_01_14_133255_create_inventories_scrap_tags_table',
                '2025_01_14_133260_create_inventories_moves_table',
                '2025_01_15_095753_create_inventories_move_lines_table',
            ])
            ->runsMigrations()
            ->hasSettings([
                '2025_01_17_094021_create_inventories_operation_settings',
                '2025_01_17_094022_create_inventories_product_settings',
                '2025_01_17_094023_create_inventories_traceability_settings',
                '2025_01_17_094024_create_inventories_warehouse_settings',
                '2025_01_17_094051_create_inventories_logistic_settings',
            ])
            ->runsSettings()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $command) {
                        if ($command->confirm('Would you like to seed the data now?')) {
                            $command->comment('Seeding data...');

                            $command->call('db:seed', [
                                '--class' => 'Webkul\\Inventory\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/inventories');
            });
    }

    public function packageBooted(): void
    {
        //
    }
}
