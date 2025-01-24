<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Clusters\Cluster;

class MyTime extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 1;

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
