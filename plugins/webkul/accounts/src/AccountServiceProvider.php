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
                '2025_02_03_131858_create_accounts_fiscal_position_taxes_table',
                '2025_02_03_144139_create_accounts_cash_roundings_table',
                '2025_02_04_104958_create_accounts_product_taxes_table',
                '2025_02_04_111337_create_product_supplier_taxes_table',
                '2025_02_04_111337_create_accounts_product_supplier_taxes_table',
                '2025_02_11_055303_create_accounts_account_moves_table',
                '2025_02_11_071210_create_accounts_account_move_lines_table',
                '2025_02_10_075022_create_accounts_payment_methods_table',
                '2025_02_10_073440_create_accounts_reconciles_table',
                '2025_02_11_041318_create_accounts_bank_statements_table',
                '2025_02_11_055302_create_accounts_bank_statement_lines_table',
                '2025_02_10_075607_create_accounts_payment_method_lines_table',
                '2025_02_11_100912_add_move_id_column_to_accounts_bank_statement_lines_table'
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
