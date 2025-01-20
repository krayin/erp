<?php

namespace Webkul\Timeoff\Filament\Pages;

use Webkul\Timeoff\Filament\Clusters\MyTime;
use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\Timeoff\Filament\Widgets\CalendarWidget;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = 'timeoff';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $cluster = MyTime::class;

    public static function getNavigationLabel(): string
    {
        return __('Time off');
    }

    public function getWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }
}
