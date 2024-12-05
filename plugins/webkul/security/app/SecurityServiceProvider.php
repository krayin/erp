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
            ])
            ->hasSettings([
                '2024_11_05_042358_create_user_settings',
            ])
            ->runsSettings();
    }

    public function packageBooted(): void {}
}
