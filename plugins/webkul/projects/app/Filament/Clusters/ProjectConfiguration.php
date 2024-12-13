<?php

namespace Webkul\Project\Filament\Clusters;

use Filament\Clusters\Cluster;

// TODO: Need to discuss with the @jitendra-webkul Sir about the implementation of this class as cluster with same name is getting issues of views.
class ProjectConfiguration extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Project';

    protected static ?int $navigationSort = 0;
}
