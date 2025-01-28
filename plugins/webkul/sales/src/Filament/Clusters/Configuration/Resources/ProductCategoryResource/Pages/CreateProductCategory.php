<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Filament\Notifications\Notification;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('sales::filament/clusters/configurations/resources/product-category/pages/create-product-category.notification.title'))
            ->body(__('sales::filament/clusters/configurations/resources/product-category/pages/create-product-category.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();

        return $data;
    }
}
