<?php

namespace Webkul\Core;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use Webkul\Core\Policies\RolePolicy;
use Webkul\Core\Livewire\AcceptInvitation;

class CoreServiceProvider extends PackageServiceProvider
{
    public static string $name = 'core';

    public static string $viewNamespace = 'core';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasRoute('web')
            ->hasMigrations([
                '2024_11_11_112529_create_user_invitations_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Livewire::component('accept-invitation', AcceptInvitation::class);

        Gate::policy(Role::class, RolePolicy::class);
    }
}