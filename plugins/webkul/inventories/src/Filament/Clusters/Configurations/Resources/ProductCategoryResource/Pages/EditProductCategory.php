<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/product-category/pages/edit-product-category.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/product-category/pages/edit-product-category.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/product-category/pages/edit-product-category.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/product-category/pages/edit-product-category.header-actions.delete.notification.body')),
                ),
        ];
    }
}
