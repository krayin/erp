<?php

namespace Webkul\Project;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class ProjectServiceProvider extends PackageServiceProvider
{
    public static string $name = 'projects';

    public static string $viewNamespace = 'projects';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        //
    }
}
