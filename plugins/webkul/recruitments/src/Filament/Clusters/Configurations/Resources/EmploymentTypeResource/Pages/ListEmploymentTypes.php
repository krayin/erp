<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\EmploymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmploymentTypes extends ListRecords
{
    protected static string $resource = EmploymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('recruitments::filament/clusters/configurations/resources/employment-type/pages/list-employment-type.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    return $data;
                }),
        ];
    }
}
