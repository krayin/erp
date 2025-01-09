<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\EditDepartment as BaseEditDepartment;

class EditDepartment extends BaseEditDepartment
{
    protected static string $resource = DepartmentResource::class;
}
