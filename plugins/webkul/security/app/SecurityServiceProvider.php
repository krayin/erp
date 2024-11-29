<?php

namespace Webkul\Security;

class SecurityServiceProvider extends PackageServiceProvider
{
    public static string $name = 'security';

    public static string $viewNamespace = 'security';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasRoute('web')
            ->hasMigrations([])
            ->runsMigrations()
            ->hasSettings([])
            ->runsSettings();
    }

    public function packageBooted(): void {}
}
