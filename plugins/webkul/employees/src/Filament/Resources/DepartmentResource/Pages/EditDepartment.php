<?php

namespace Webkul\Employee\Filament\Resources\DepartmentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Employee\Filament\Resources\DepartmentResource;
use Filament\Notifications\Notification;

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
            ->title(__('employees::filament/resources/department/pages/edit-department.notification.title'))
            ->body(__('employees::filament/resources/department/pages/edit-department.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/resources/department/pages/edit-department.header-actions.delete.notification.title'))
                        ->body(__('employees::filament/resources/department/pages/edit-department.header-actions.delete.notification.body')),
                ),
        ];
    }
}
