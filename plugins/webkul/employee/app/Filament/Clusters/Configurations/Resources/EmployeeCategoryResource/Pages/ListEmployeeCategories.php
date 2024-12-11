<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\EmployeeCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmployeeCategoryResource;

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

                    $colors = [
                        'danger'  => 'Danger',
                        'gray'    => 'Gray',
                        'info'    => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                    ];

                    $data['color'] = $data['color'] ?? collect($colors)->keys()->random();

                    return $data;
                }),
        ];
    }
}
