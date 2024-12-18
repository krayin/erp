<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;
use Webkul\Employee\Models\Calendar;

class ListCalendars extends ListRecords
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(Calendar::count()),
            'archived' => Tab::make('Archived')
                ->badge(Calendar::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
