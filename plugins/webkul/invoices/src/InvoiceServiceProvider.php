<?php

namespace Webkul\Invoice;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class InvoiceServiceProvider extends PackageServiceProvider
{
    public static string $name = 'invoices';

    public static string $viewNamespace = 'invoices';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([])
            ->runsMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->askToRunMigrations()
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $command) {
                        if ($command->confirm('Would you like to seed the data now?')) {
                            $command->comment('Seeding data...');

                            $command->call('db:seed', [
                                '--class' => 'Webkul\\Invoice\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/invoices');
            });
    }
}
