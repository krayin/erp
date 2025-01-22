<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;

class ViewLot extends ViewRecord
{
    protected static string $resource = LotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/products/resources/lot/pages/view-lot.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/products/resources/lot/pages/view-lot.header-actions.delete.notification.body')),
                ),
        ];
    }
}
