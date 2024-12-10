<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\EmploymentTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Employee\Resources\EmploymentTypeResource;
use Webkul\Employee\Models\EmploymentType;

class ListEmploymentTypes extends ListRecords
{
    protected static string $resource = EmploymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['sequence'] = EmploymentType::max('sequence') + 1;

                    return $data;
                }),
        ];
    }
}
