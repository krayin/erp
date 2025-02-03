<?php

namespace Webkul\Account;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class AccountServiceProvider extends PackageServiceProvider
{
    public static string $name = 'accounts';

    public static string $viewNamespace = 'accounts';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_01_29_044430_create_accounts_payment_terms_table',
                '2025_01_29_064646_create_accounts_payment_due_terms_table',
                '2025_01_29_134156_create_accounts_incoterms_table',
                '2025_01_29_134157_create_accounts_tax_groups_table',
                '2025_01_30_054952_create_accounts_accounts_table',
                '2025_01_30_061945_create_accounts_account_tags_table',
                '2025_01_30_083208_create_accounts_taxes_table',
                '2025_01_30_123324_create_accounts_tax_partitions_table',
                '2025_01_31_073645_create_accounts_journals_table',
                '2025_01_31_095921_create_accounts_journal_accounts_table',
                '2025_01_31_125419_create_accounts_tax_taxes_table',
                '2025_02_03_054613_create_accounts_account_taxes_table',
                '2025_02_03_055117_create_accounts_account_account_tags_table',
                '2025_02_03_055709_create_accounts_account_journals_table',
                '2025_02_03_121847_create_accounts_fiscal_positions_table',
                '2025_02_03_131858_create_accounts_fiscal_position_taxes_table'
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
                                '--class' => 'Webkul\\Account\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/accounts');
            });
    }
}
