<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\EmploymentTypeResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages\ListEmploymentTypes as BaseListEmploymentTypes;

class ListEmploymentTypes extends BaseListEmploymentTypes
{
    protected static string $resource = EmploymentTypeResource::class;
}
