<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewMyAllocation extends ViewRecord
{
    protected static string $resource = MyAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time_off::filament/clusters/my-time/resources/my-allocation/pages/view-allocation.header-actions.delete.notification.title'))
                        ->body(__('time_off::filament/clusters/my-time/resources/my-allocation/pages/view-allocation.header-actions.delete.notification.body'))
                ),
        ];
    }
}
