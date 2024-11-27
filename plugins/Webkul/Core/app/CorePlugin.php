<?php

namespace Webkul\Core;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Webkul\Core\Settings\UserSettings;

class CorePlugin implements Plugin
{
    public function getId(): string
    {
        return 'core';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->passwordReset()
            ->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Core\\Filament\\Resources')
            ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Core\\Filament\\Pages')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Core\\Filament\\Clusters')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Core\\Filament\\Widgets');

        if (! app(UserSettings::class)->enable_reset_password) {
            $panel->passwordReset(false);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    protected function getPluginBasePath($path = null): string
    {
        $reflector = new \ReflectionClass(get_class($this));

        return dirname($reflector->getFileName()).($path ?? '');
    }
}
