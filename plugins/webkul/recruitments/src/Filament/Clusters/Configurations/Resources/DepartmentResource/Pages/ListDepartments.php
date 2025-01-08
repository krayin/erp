<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ListDepartments extends ListRecords
{
    use HasTableViews;

    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('recruitments::filament/clusters/configurations/resources/department/pages/list-department.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'archived' => PresetView::make('Archived')
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->label(__('recruitments::filament/clusters/configurations/resources/department/pages/list-department.tabs.archived-departments'))
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }
}
