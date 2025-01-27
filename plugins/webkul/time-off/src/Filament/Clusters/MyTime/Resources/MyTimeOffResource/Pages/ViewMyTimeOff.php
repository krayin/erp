<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewMyTimeOff extends ViewRecord
{
    protected static string $resource = MyTimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time_off::filament/clusters/my-time/resources/my-time-off/pages/view-time-off.header-actions.delete.notification.title'))
                        ->body(__('time_off::filament/clusters/my-time/resources/my-time-off/pages/view-time-off.header-actions.delete.notification.body'))
                ),
        ];
    }
}
