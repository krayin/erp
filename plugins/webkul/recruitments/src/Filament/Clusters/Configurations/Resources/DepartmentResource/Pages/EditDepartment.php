<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;

class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('recruitments::filament/clusters/configurations/resources/department/pages/edit-department.notification.title'))
            ->body(__('recruitments::filament/clusters/configurations/resources/department/pages/edit-department.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()
                ->setResource(static::$resource),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/configurations/resources/department/pages/edit-department.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/configurations/resources/department/pages/edit-department.header-actions.delete.notification.body')),
                ),
        ];
    }
}
