<?php

namespace Webkul\Sale;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class SaleServiceProvider extends PackageServiceProvider
{
    public static string $name = 'sales';

    public static string $viewNamespace = 'sales';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_01_28_061110_create_sales_teams_table',
                '2025_01_28_074033_create_sales_team_members_table',
                '2025_01_28_102329_create_add_columns_to_product_categories_table',
                '2025_01_28_122700_create_sales_order_templates_table',
                '2025_02_04_082243_add_sales_fields_to_products_table',
                '2025_02_05_053212_create_sales_orders_table',
            ])
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $command) {
                        if ($command->confirm('Would you like to seed the data now?')) {
                            $command->comment('Seeding data...');

                            $command->call('db:seed', [
                                '--class' => 'Webkul\\Sale\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/sales');
            });
    }
}
