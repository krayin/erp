<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Webkul\Employee\Models\ActivityType;

class ListActivityTypes extends ListRecords
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(ActivityType::count()),
            'archived' => Tab::make('Archived')
                ->badge(ActivityType::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
