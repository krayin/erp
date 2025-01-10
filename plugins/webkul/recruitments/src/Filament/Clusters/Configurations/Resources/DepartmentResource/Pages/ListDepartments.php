<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\ListDepartments as BaseListDepartments;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;

class ListDepartments extends BaseListDepartments
{
    protected static string $resource = DepartmentResource::class;
}
