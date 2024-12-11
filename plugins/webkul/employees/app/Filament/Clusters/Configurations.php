<?php

namespace Webkul\Employee\Filament\Clusters;

use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Employees';

    protected static ?int $navigationSort = 0;
}
