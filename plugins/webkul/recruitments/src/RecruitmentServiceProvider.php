<?php

namespace Webkul\Recruitment;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class RecruitmentServiceProvider extends PackageServiceProvider
{
    public static string $name = 'recruitments';

    public static string $viewNamespace = 'recruitments';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_01_06_133002_create_recruitments_stages_table',
                '2025_01_07_053021_create_recruitments_stages_jobs_table',
            ])
            ->runsMigrations();
    }
}
