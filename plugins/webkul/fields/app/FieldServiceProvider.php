<?php

namespace Webkul\Field;

use Illuminate\Support\Facades\Gate;
use Webkul\Field\Models\Field;
use Webkul\Field\Policies\FieldPolicy;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class FieldServiceProvider extends PackageServiceProvider
{
    public static string $name = 'fields';

    public static string $viewNamespace = 'fields';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
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
