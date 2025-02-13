<?php

namespace Webkul\Invoice\Filament\Clusters;

use Filament\Clusters\Cluster;

class Customer extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-users';


    public static function getNavigationLabel(): string
    {
        return __('Customers');
    }

    public static function getNavigationGroup(): string
    {
        return __('invoices::filament/clusters/configurations.navigation.group');
    }
}
