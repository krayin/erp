<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource;

class EditProductAttribute extends EditRecord
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('sales::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.notification.title'))
            ->body(__('sales::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.header-actions.delete.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/product-attribute/pages/edit-product-attribute.header-actions.delete.notification.body')),
                ),
        ];
    }
}
