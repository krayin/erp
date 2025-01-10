<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\CreateDepartment as BaseCreateDepartment;

class CreateDepartment extends BaseCreateDepartment
{
    protected static string $resource = DepartmentResource::class;
}
