<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\Pages;

use Webkul\Employee\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
