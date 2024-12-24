<?php

namespace Webkul\Support\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('support::app.filament.clusters.settings.name');
    }

    public static function getNavigationGroup(): string
    {
        return __('support::app.filament.clusters.settings.group');
    }
}
