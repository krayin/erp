<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\DepartmentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Employee\Filament\Clusters\Employee\Resources\DepartmentResource;

class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
