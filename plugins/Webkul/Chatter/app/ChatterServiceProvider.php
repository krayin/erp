<?php

namespace Webkul\Chatter;

use Livewire\Livewire;
use Webkul\Chatter\Livewire\ChatterPanel;
use Webkul\Core\Package;
use Webkul\Core\PackageServiceProvider;

class ChatterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'chatter';

    public static string $viewNamespace = 'chatter';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasMigrations([
                '2024_11_18_081018_create_tasks_table',
                '2024_11_18_081030_create_chats_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Livewire::component('chatter-panel', ChatterPanel::class);
    }
}
