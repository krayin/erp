<?php

namespace Webkul\Product;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ProductPlugin implements Plugin
{
    public function getId(): string
    {
        return 'products';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void
    {
        //
    }
}
