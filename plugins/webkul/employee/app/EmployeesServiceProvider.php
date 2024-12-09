<?php

namespace Webkul\Employee;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class EmployeesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'employee';

    public static string $viewNamespace = 'employee';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2024_12_09_051916_create_departments_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
