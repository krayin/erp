<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Clusters\Cluster;

class MyTime extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getSlug(): string
    {
        return 'time-off/dashboard';
    }

    public static function getNavigationLabel(): string
    {
        return __('Time Off');
    }

    public static function getNavigationGroup(): string
    {
        return __('Time Off');
    }
}
