<?php

namespace Webkul\Project\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\Support\Filament\Clusters\Dashboard as DashboardCluster;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = 'project';

    protected static ?string $navigationLabel = 'Project';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $cluster = DashboardCluster::class;
}
