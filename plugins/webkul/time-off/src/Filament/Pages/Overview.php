<?php

namespace Webkul\TimeOff\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\TimeOff\Filament\Widgets\OverviewCalendarWidget;

class Overview extends BaseDashboard
{
    protected static string $routePath = 'time-off';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('time_off::filament/pages/overview.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('time_off::filament/pages/overview.navigation.group');
    }

    public function getWidgets(): array
    {
        return [
            OverviewCalendarWidget::class,
        ];
    }
}
