<?php

namespace Webkul\Payment;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class PaymentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'payments';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_02_10_131418_create_payments_payments_table',
                '2025_02_11_103602_create_payments_payment_transactions_table',
                '2025_02_11_101123_create_payments_payment_tokens_table'
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
                                '--class' => 'Webkul\\Payment\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/payments');
            });
    }
}
