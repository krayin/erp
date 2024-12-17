<?php

namespace Webkul\Project\Filament\Clusters;

use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static ?string $slug = 'project/configurations';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Project';

    protected static ?int $navigationSort = 0;
}
