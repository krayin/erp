<?php

namespace Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource;

class ListEmployeeSkills extends ListRecords
{
    protected static string $resource = EmployeeSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
