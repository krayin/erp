<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\ListDepartments as BaseListDepartments;

class ListDepartments extends BaseListDepartments
{
    protected static string $resource = DepartmentResource::class;
}
