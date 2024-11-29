<?php

namespace Webkul\Fields;

use Illuminate\Support\Facades\Gate;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;
use Webkul\Fields\Models\Field;
use Webkul\Fields\Policies\FieldPolicy;

class FieldsServiceProvider extends PackageServiceProvider
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
