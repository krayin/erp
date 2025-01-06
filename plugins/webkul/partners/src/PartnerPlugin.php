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
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
