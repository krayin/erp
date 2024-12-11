<?php

namespace Webkul\Employee;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class EmployeeServiceProvider extends PackageServiceProvider
{
    public static string $name = 'employees';

    public static string $viewNamespace = 'employees';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2024_12_11_051916_create_departments_table',
                '2024_12_11_073130_create_employment_types_table',
                '2024_12_11_075004_create_skill_types_table',
                '2024_12_11_075011_create_skill_levels_table',
                '2024_12_11_075017_create_skills_table',
                '2024_12_11_120605_create_departure_reasons_table',
                '2024_12_12_114620_create_activity_plans_table',
                '2024_12_12_115256_create_activity_types_table',
                '2024_12_12_115728_create_activity_plan_templates_table',
                '2024_12_11_045350_create_work_locations_table',
                '2024_12_11_054555_create_employee_categories_table',
                '2024_12_11_081046_create_employee_job_positions_table',
                '2024_12_11_100426_create_calendars_table',
                '2024_12_11_100435_create_calendar_attendances_table',
                '2024_12_11_100442_create_calendar_leaves_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
