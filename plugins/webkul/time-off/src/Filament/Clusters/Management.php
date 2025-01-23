<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Clusters\Cluster;

class Management extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function getSlug(): string
    {
        return 'time-off/management';
    }

    public static function getNavigationLabel(): string
    {
        return __('Management');
    }

    public static function getNavigationGroup(): string
    {
        return __('Time Off');
    }
}
