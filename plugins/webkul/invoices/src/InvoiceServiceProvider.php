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
            ->hasMigrations([
                '2025_01_29_044430_create_invoices_payment_terms_table',
                '2025_01_29_064646_create_invoices_payment_due_terms_table',
                '2025_01_29_134156_create_invoices_incoterms_table',
                '2025_01_29_134157_create_invoices_tax_groups_table',
                '2025_01_30_054952_create_invoices_accounts_table',
                '2025_01_30_061945_create_invoices_account_tags_table',
                '2025_01_30_083208_create_invoices_taxes_table',
                '2025_01_30_123324_create_invoices_tax_partitions_table',
                '2025_01_31_073645_create_invoices_journals_table',
                '2025_01_31_095921_create_invoices_journal_accounts_table',
                '2025_01_31_125419_create_invoices_tax_tax_relations_table',
                '2025_02_03_054613_create_invoices_account_taxes_table',
                '2025_02_03_055117_create_invoices_account_account_tags_table',
                '2025_02_03_055709_create_invoices_account_journals_table',
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
                                '--class' => 'Webkul\\Invoice\\Database\Seeders\\DatabaseSeeder',
                            ]);
                        }
                    })
                    ->askToStarRepoOnGitHub('krayin/invoices');
            });
    }
}
