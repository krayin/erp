<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\ViewDepartment as BaseViewDepartment;

class ViewDepartment extends BaseViewDepartment
{
    protected static string $resource = DepartmentResource::class;
}
