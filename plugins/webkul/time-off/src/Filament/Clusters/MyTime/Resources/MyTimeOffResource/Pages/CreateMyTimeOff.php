<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMyTimeOff extends CreateRecord
{
    protected static string $resource = MyTimeOffResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        if ($user?->employee) {
            $data['employee_id'] = $user->employee->id;
            $data['department_id'] = $user->employee->department->id;
        }

        return $data;
    }
}
