<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource;

class ViewAllocation extends ViewRecord
{
    protected static string $resource = AllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time_off::filament/clusters/management/resources/allocation/pages/view-allocation.header-actions.delete.notification.title'))
                        ->body(__('time_off::filament/clusters/management/resources/allocation/pages/view-allocation.header-actions.delete.notification.body'))
                ),
        ];
    }
}
