<?php

namespace Webkul\Employee\Filament\Clusters;

use Filament\Clusters\Cluster;

class Reportings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Employees';

    protected static ?int $navigationSort = 3;
}
