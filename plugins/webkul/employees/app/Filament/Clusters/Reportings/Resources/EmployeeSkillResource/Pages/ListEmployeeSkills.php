<?php

namespace Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource\Pages;

use Webkul\Employee\Filament\Clusters\Reportings\Resources\EmployeeSkillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
