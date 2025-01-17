<?php

namespace Webkul\Timeoff\Filament\Clusters;

use Filament\Clusters\Cluster;

class MyTime extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function getSlug(): string
    {
        return 'timeoff/dashboard';
    }

    public static function getNavigationLabel(): string
    {
        return __('Time off');
    }

    public static function getNavigationGroup(): string
    {
        return __('Time off');
    }
}
