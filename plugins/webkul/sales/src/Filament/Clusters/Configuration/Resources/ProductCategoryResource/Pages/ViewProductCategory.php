<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewProductCategory extends ViewRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/configurations/resources/product-category/pages/view-product-category.header-actions.delete.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/product-category/pages/view-product-category.header-actions.delete.notification.body'))
                )
        ];
    }
}
