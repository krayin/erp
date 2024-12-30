<?php

namespace Webkul\Timesheet;

use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class TimesheetServiceProvider extends PackageServiceProvider
{
    public static string $name = 'timesheets';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        //
    }
}
