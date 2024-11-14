<?php

namespace Webkul\Field;

use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Webkul\Core\Package;
use Webkul\Core\PackageServiceProvider;
use Webkul\Field\Models\Field;
use Webkul\Field\Policies\FieldPolicy;

class FieldServiceProvider extends PackageServiceProvider
{
    public static string $name = 'field';

    public static string $viewNamespace = 'field';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasMigrations([
                '2024_11_13_052541_create_custom_fields_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Gate::policy(Field::class, FieldPolicy::class);
    }
}
