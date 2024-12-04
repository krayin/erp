<?php

namespace Webkul\Security\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function getNavigationLabel(): string
    {
        return __('security::app.filament.clusters.settings.name');
    }

    public static function getNavigationGroup(): string
    {
        return __('security::app.filament.clusters.settings.group');
    }
}
