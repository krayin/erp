<?php

namespace Webkul\TimeOff\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\TimeOff\Filament\Widgets\OverviewCalendarWidget;

class Overview extends BaseDashboard
{
    protected static string $routePath = 'time-off';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Overview');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Time Off');
    }

    public function getWidgets(): array
    {
        return [
            OverviewCalendarWidget::class,
        ];
    }
}
