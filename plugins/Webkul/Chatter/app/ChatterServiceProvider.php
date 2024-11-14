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
                '2024_11_14_095310_create_tasks_table',
                '2024_11_14_095321_create_task_followers_table',
                '2024_11_14_095328_create_messages_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Livewire::component('chatter-panel', ChatterPanel::class);
    }
}
