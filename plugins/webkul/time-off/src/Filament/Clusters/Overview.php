<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Clusters\Cluster;

class Overview extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    public static function getSlug(): string
    {
        return 'time-off/overview';
    }

    public static function getNavigationLabel(): string
    {
        return __('Overview');
    }

    public static function getNavigationGroup(): string
    {
        return __('Time Off');
    }
}
