<?php

namespace Webkul\Chatter;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Livewire\Livewire;
use Webkul\Chatter\Livewire\ChatterPanel;

class ChatterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'chatter';

    public static string $viewNamespace = 'chatter';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasMigrations([
                '2024_11_13_113235_create_messages_table',
                '2024_11_13_113246_create_notes_table',
                '2024_11_13_113247_create_activities_table',
                '2024_11_13_113247_create_followers_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Livewire::component('chatter-panel', ChatterPanel::class);
    }
}