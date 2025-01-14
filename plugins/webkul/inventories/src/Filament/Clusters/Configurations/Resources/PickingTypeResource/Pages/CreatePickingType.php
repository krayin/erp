<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource;

class CreatePickingType extends CreateRecord
{
    protected static string $resource = PickingTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/picking-type/pages/create-picking-type.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/picking-type/pages/create-picking-type.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();

        return $data;
    }
}
