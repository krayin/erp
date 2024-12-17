<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Resources\EmployeeResource;
use Webkul\TableViews\Components\PresetView;
use Webkul\TableViews\Filament\Traits\HasTableViews;

class ListEmployees extends ListRecords
{
    use HasTableViews;

    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'my_team' => PresetView::make('My Team')
                ->icon('heroicon-m-users')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    $user = Auth::user();

                    return $query->where('parent_id', $user->employee->id);
                }),
            'my_department' => PresetView::make('My Department')
                ->icon('heroicon-m-user-group')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    $user = Auth::user();

                    return $query->where('parent_id', $user->employee->id);
                }),
            'archived' => PresetView::make('Archived')
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
