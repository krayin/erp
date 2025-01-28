<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('sales::filament/clusters/configurations/resources/product-category/pages/edit-product-category.notification.title'))
            ->body(__('sales::filament/clusters/configurations/resources/product-category/pages/edit-product-category.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/configurations/resources/product-category/pages/edit-product-category.header-actions.delete.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/product-category/pages/edit-product-category.header-actions.delete.notification.body'))
                )
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
