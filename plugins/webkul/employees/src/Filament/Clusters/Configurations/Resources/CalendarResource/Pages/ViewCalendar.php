<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;

class ViewCalendar extends ViewRecord
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/activity-plan/pages/view-activity-plan.header-actions.delete.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/activity-plan/pages/view-activity-plan.header-actions.delete.notification.body')),
                ),
        ];
    }
}
