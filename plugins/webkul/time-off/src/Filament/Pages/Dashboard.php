<?php

namespace Webkul\TimeOff\Filament\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime;
use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\TimeOff\Filament\Widgets\CalendarWidget;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = 'time-off';

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $cluster = MyTime::class;

    public static function getNavigationLabel(): string
    {
        return __('Time Off');
    }

    public function getWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }
}
