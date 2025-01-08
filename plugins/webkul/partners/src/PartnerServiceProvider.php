<?php

namespace Webkul\Partner;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class PartnerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'partners';

    public static string $viewNamespace = 'partners';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2024_12_11_101127_create_partners_industries_table',
                '2024_12_11_101127_create_partners_titles_table',
                '2024_12_11_101220_create_partners_partners_table',
                '2024_12_11_101420_create_partners_bank_accounts_table',
                '2024_12_11_101644_create_partners_addresses_table',
                '2024_12_11_101927_create_partners_tags_table',
                '2024_12_11_111929_create_partners_partner_tag_table',
                '2025_01_07_073804_add_sub_type_column_to_partners_partners_table',
                '2025_01_07_081558_remove_nullable_name_column_to_partners_addresses_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
