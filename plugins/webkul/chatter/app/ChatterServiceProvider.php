<?php

namespace Webkul\Chatter;

use Livewire\Livewire;
use Webkul\Chatter\Livewire\ChatterPanel;
use Webkul\Chatter\Livewire\Follower;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

class ChatterServiceProvider extends PackageServiceProvider
{
    public static string $name = 'chatter';

    public static string $viewNamespace = 'chatter';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2024_11_18_124832_create_followers_table',

                '2024_12_23_062355_create_chatter_messages_table',
                '2024_12_23_080148_create_chatter_attachments_table',
            ])
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Livewire::component('chatter-panel', ChatterPanel::class);
        Livewire::component('followers', Follower::class);
    }
}
