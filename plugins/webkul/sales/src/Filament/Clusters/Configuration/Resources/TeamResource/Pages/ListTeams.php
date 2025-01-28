<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\TeamResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Sale\Models\Team;
use Filament\Resources\Components\Tab;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

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
            'all' => Tab::make(__('All'))
                ->badge(Team::count()),
            'archived' => Tab::make(__('Archived'))
                ->badge(Team::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
