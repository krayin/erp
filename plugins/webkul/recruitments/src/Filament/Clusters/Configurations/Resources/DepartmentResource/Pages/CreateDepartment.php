<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Filament\Notifications\Notification;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('recruitments::filament/clusters/configurations/resources/department/pages/create-department.notification.title'))
            ->body(__('recruitments::filament/clusters/configurations/resources/department/pages/create-department.notification.body'));
    }
}
