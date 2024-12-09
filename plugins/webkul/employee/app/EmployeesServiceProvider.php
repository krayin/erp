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
                '2024_12_09_073130_create_employment_types_table',
                '2024_12_09_075004_create_skill_types_table',
                '2024_12_09_075011_create_skill_levels_table',
                '2024_12_09_075017_create_skills_table',
                '2024_12_09_120605_create_departure_reasons_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
