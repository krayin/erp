<?php

namespace Webkul\Support;

use Filament\Contracts\Plugin;
use Filament\Panel;

class SupportPlugin implements Plugin
{
    public function getId(): string
    {
        return 'support';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        //
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
