<?php

namespace Webkul\Fields;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FieldsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'field';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Fields\\Filament\\Resources')
            ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Fields\\Filament\\Pages')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Fields\\Filament\\Clusters')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Fields\\Filament\\Widgets');
    }

    public function boot(Panel $panel): void
    {
        //
    }

    protected function getPluginBasePath($path = null): string
    {
        $reflector = new \ReflectionClass(get_class($this));

        return dirname($reflector->getFileName()) . ($path ?? '');
    }
}
