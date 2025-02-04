<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource;

class CreateProductAttribute extends CreateRecord
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('sales::filament/clusters/configurations/resources/product-attribute/pages/create-product-attribute.notification.title'))
            ->body(__('sales::filament/clusters/configurations/resources/product-attribute/pages/create-product-attribute.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();

        return $data;
    }
}
