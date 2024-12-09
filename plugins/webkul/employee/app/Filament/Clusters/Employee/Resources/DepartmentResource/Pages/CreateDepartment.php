<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\DepartmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Employee\Filament\Clusters\Employee\Resources\DepartmentResource;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
