<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;
use Filament\Notifications\Notification;

class CreateCalendar extends CreateRecord
{
    protected static string $resource = CalendarResource::class;

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('employees::filament/clusters/configurations/resources/calendar/pages/create-calendar.notification.title'))
            ->body(__('employees::filament/clusters/configurations/resources/calendar/pages/create-calendar.notification.body'));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
