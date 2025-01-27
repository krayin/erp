<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditMyAllocation extends EditRecord
{
    protected static string $resource = MyAllocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('time_off::filament/clusters/my-time/resources/my-allocation/pages/edit-allocation.notification.title'))
            ->body(__('time_off::filament/clusters/my-time/resources/my-allocation/pages/edit-allocation.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time_off::filament/clusters/my-time/resources/my-allocation/pages/edit-allocation.header-actions.delete.notification.title'))
                        ->body(__('time_off::filament/clusters/my-time/resources/my-allocation/pages/edit-allocation.header-actions.delete.notification.body'))
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        if ($user?->employee) {
            $data['employee_id'] = $user->employee->id;
        }

        return $data;
    }
}
