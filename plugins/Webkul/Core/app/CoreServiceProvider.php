<?php

namespace Webkul\Core;

use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Webkul\Core\Livewire\AcceptInvitation;
use Webkul\Core\Policies\RolePolicy;

class CoreServiceProvider extends PackageServiceProvider
{
    public static string $name = 'core';

    public static string $viewNamespace = 'core';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasRoute('web')
            ->hasMigrations([
                '2024_11_11_112529_create_user_invitations_table',
                '2024_11_12_125715_create_teams_table',
                '2024_11_12_130019_create_user_team_table',
            ])
            ->runsMigrations()
            ->hasSettings([
                '2024_11_05_042358_create_user_settings',
            ])
            ->runsSettings();
    }

    public function packageBooted(): void
    {
        Livewire::component('accept-invitation', AcceptInvitation::class);

        Gate::policy(Role::class, RolePolicy::class);
    }
}
