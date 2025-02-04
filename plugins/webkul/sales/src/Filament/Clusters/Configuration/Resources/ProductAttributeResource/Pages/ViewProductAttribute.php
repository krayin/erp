<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource;

class ViewProductAttribute extends ViewRecord
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/configurations/resources/product-attribute/pages/view-product-attributes.header-actions.delete.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/product-attribute/pages/view-product-attributes.header-actions.delete.notification.body')),
                ),
        ];
    }
}
