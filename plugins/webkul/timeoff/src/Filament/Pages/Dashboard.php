<?php

namespace Webkul\Timeoff\Filament\Pages;

use Filament\Pages\Page;
use Webkul\Timeoff\Filament\Clusters\MyTime;
use Webkul\Timeoff\Filament\Widgets\CalendarWidget;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'webkul.timeoff.filament.pages.dashboard';

    protected static ?string $cluster = MyTime::class;

    public static function getSlug(): string
    {
        return 'timeoff/dashboard';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }
}
