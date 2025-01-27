<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;

class ViewTimeOff extends ViewRecord
{
    protected static string $resource = TimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time_off::filament/clusters/management/resources/time-off/pages/view-time-off.header-actions.delete.notification.title'))
                        ->body(__('time_off::filament/clusters/management/resources/time-off/pages/view-time-off.header-actions.delete.notification.body'))
                ),
        ];
    }
}
