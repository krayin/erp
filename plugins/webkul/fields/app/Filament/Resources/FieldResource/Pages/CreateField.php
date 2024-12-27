<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Webkul\Field\FieldsColumnManager;
use Webkul\Field\Filament\Resources\FieldResource;

class CreateField extends CreateRecord
{
    protected static string $resource = FieldResource::class;

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('fields::filament/resources/field/pages/create-field.notification.title'))
            ->body(__('fields::filament/resources/field/pages/create-field.notification.body'));
    }

    protected function afterCreate(): void
    {
        FieldsColumnManager::createColumn($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
