<?php

namespace Webkul\Support;

use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Webkul\Security\Livewire\AcceptInvitation;
use Webkul\Security\Policies\RolePolicy;
use Webkul\Support\Console\Commands\InstallERP;

class SupportServiceProvider extends PackageServiceProvider
{
    public static string $name = 'support';

    public static string $viewNamespace = 'support';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasMigrations([])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Livewire::component('accept-invitation', AcceptInvitation::class);

        Gate::policy(Role::class, RolePolicy::class);
    }

    public function packageRegistered(): void
    {
        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallERP::class,
            ]);
        }
    }
}
