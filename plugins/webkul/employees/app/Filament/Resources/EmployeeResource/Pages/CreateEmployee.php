<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Employee\Filament\Resources\EmployeeResource;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}
