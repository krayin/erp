<?php

namespace Webkul\Sale\Filament\Pages;

use Filament\Pages\Dashboard as Page;
use Webkul\Sale\Filament\Widgets;
use Webkul\Sale\Filament\Clusters\Orders as OrderClusters;

class SaleTeam extends Page
{
    use Page\Concerns\HasFiltersForm;

    protected static string $routePath = 'sales-teams';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $cluster = OrderClusters::class;

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Sales Teams');
    }

    public function getWidgets(): array
    {
        return [
            Widgets\SaleTeamWidget::class,
        ];
    }
}
