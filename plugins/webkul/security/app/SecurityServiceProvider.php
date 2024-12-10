<?php

namespace Webkul\Security;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class SecurityServiceProvider extends PackageServiceProvider
{
    public static string $name = 'security';

    public static string $viewNamespace = 'security';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web')
            ->runsMigrations()
            ->hasMigrations([
                '2024_11_11_112529_create_user_invitations_table',
                '2024_11_12_125715_create_teams_table',
                '2024_11_12_130019_create_user_team_table',
                '2024_12_10_092651_create_countries_table',
                '2024_12_10_092657_create_states_table',
                '2024_12_06_061927_create_currencies_table',
                '2024_12_06_111949_add_default_company_id_column_to_users_table',
                '2024_12_10_100813_create_companies_table',
                '2024_12_10_100833_create_branches_table',
                '2024_12_10_100944_create_user_allowed_companies_table',
                '2024_12_10_101127_create_add_columns_to_users_table',
                '2024_12_10_114620_create_activity_plans_table',
                '2024_12_10_115256_create_activity_types_table',
                '2024_12_10_115728_create_activity_plan_templates_table'
            ])
            ->hasSettings([
                '2024_11_05_042358_create_user_settings',
            ])
            ->runsSettings();
    }

    public function packageBooted(): void {}
}
