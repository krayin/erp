<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackagingResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackagingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPackaging extends EditRecord
{
    protected static string $resource = PackagingResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/packaging/pages/edit-packaging.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/packaging/pages/edit-packaging.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/packaging/pages/edit-packaging.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/packaging/pages/edit-packaging.header-actions.delete.notification.body')),
                ),
        ];
    }
}
