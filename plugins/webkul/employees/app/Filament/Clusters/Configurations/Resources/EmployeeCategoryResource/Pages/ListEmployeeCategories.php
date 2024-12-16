<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\EmployeeCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmployeeCategoryResource;
use Webkul\Employee\Models\EmployeeCategory;

class ListEmployeeCategories extends ListRecords
{
    protected static string $resource = EmployeeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = Auth::user()->id;

                    $data['color'] = $data['color'] ?? collect(Enums\Colors::options())->keys()->random();

                    return $data;
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(EmployeeCategory::count()),
            'archived' => Tab::make('Archived')
                ->badge(EmployeeCategory::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
