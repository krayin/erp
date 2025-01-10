<?php

namespace Webkul\Recruitment\Filament\Clusters;

use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 2;

    public static function getSlug(): string
    {
        return 'recruitments/configurations';
    }

    public static function getNavigationLabel(): string
    {
        return __('recruitments::filament/clusters/configurations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations.navigation.group');
    }
}
