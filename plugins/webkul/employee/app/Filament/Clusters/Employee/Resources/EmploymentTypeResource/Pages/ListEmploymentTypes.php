<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\EmploymentTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Employee\Resources\EmploymentTypeResource;

class ListEmploymentTypes extends ListRecords
{
    protected static string $resource = EmploymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus-circle'),
        ];
    }
}
