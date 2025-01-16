<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->prepareData($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->prepareData($data);
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }

    public function prepareData($data): array
    {
        $model = $this->record;

        return array_merge($data, [
            'no_of_employee'       => $model->no_of_employee,
            'no_of_hired_employee' => $model->no_of_hired_employee,
            'expected_employees'   => $model->expected_employees,
        ]);
    }
}
