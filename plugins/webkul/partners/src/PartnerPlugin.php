<?php

namespace Webkul\Partner;

use Filament\Contracts\Plugin;
use Filament\Panel;

class PartnerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'partners';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Partner\\Filament\\Resources')
            ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Partner\\Filament\\Pages')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Partner\\Filament\\Clusters')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Partner\\Filament\\Widgets');
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
