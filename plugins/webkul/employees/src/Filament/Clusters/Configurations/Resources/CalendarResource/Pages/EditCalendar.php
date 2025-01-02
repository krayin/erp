<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;

class EditCalendar extends EditRecord
{
    protected static string $resource = CalendarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('employees::filament/clusters/configurations/resources/calendar/pages/edit-calendar.notification.title'))
            ->body(__('employees::filament/clusters/configurations/resources/calendar/pages/edit-calendar.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/calendar/pages/edit-calendar.header-actions.delete.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/calendar/pages/edit-calendar.header-actions.delete.notification.body')),
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
