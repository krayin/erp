<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource;

class ViewInternal extends ViewRecord
{
    protected static string $resource = InternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/internal/pages/view-internal.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/internal/pages/view-internal.header-actions.delete.notification.body')),
                ),
        ];
    }
}
