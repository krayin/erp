<?php

namespace Webkul\Employee\Filament\Clusters;

use Filament\Clusters\Cluster;

class Employee extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Employee';

    protected static ?int $navigationSort = 0;
}
