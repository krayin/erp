<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource;
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

                    $data['code'] = $data['code'] ?? $data['name'];

                    $data['user_id'] = Auth::user()->id;

                    return $data;
                }),
        ];
    }
}
