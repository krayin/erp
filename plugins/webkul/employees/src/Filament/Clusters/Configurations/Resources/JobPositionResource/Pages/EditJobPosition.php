<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\Employee\Models\EmployeeJobPosition;

class EditJobPosition extends EditRecord
{
    protected static string $resource = JobPositionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('employees::filament/clusters/configurations/resources/job-position/pages/edit-job-position.notification.title'))
            ->body(__('employees::filament/clusters/configurations/resources/job-position/pages/edit-job-position.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/job-position/pages/edit-job-position.header-actions.delete.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/job-position/pages/edit-job-position.header-actions.delete.notification.body'))
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        $data['sort'] = EmployeeJobPosition::max('sort') + 1;

        return $data;
    }
}
