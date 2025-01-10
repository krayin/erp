<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProductAttribute extends EditRecord
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.header-actions.delete.notification.body')),
                ),
        ];
    }
}
