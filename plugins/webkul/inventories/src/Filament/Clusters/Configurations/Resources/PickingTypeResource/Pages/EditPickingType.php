<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPickingType extends EditRecord
{
    protected static string $resource = PickingTypeResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/picking-type/pages/edit-picking-type.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/picking-type/pages/edit-picking-type.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/picking-type/pages/edit-picking-type.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/picking-type/pages/edit-picking-type.header-actions.delete.notification.body')),
                ),
        ];
    }
}
