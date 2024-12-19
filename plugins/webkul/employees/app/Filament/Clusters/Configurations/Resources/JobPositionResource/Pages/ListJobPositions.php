<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\TableViews\Components\PresetView;
use Webkul\TableViews\Filament\Traits\HasTableViews;

class ListJobPositions extends ListRecords
{
    use HasTableViews;

    protected static string $resource = JobPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    return $data;
                }),
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'my_department' => PresetView::make('My Department')
                ->icon('heroicon-m-user-group')
                ->favorite()
                ->modifyQueryUsing(function ($query) {
                    $user = Auth::user();

                    return $query->whereIn('department_id', $user->departments->pluck('id'));
                }),
            'archived_projects' => PresetView::make('Archived')
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
