<?php

namespace Webkul\Employee\Filament\Resources\DepartmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Employee\Filament\Resources\DepartmentResource;
use Filament\Notifications\Notification;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('employees::filament/resources/department/pages/create-department.notification.title'))
            ->body(__('employees::filament/resources/department/pages/create-department.notification.body'));
    }
}
