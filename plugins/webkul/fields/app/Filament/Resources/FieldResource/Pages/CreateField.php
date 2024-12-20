<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Field\FieldsColumnManager;
use Webkul\Field\Filament\Resources\FieldResource;

class CreateField extends CreateRecord
{
    protected static string $resource = FieldResource::class;

    protected function afterCreate(): void
    {
        FieldsColumnManager::createColumn($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
