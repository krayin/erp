<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Field\FieldColumnManager;
use Webkul\Field\Filament\Resources\FieldResource;

class CreateField extends CreateRecord
{
    protected static string $resource = FieldResource::class;

    protected function afterCreate(): void
    {
        FieldColumnManager::createColumn($this->record);
    }
}
