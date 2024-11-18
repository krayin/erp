<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Webkul\Field\Filament\Resources\FieldResource;
use Webkul\Field\FieldColumnManager;
use Filament\Resources\Pages\CreateRecord;

class CreateField extends CreateRecord
{
    protected static string $resource = FieldResource::class;
 
    protected function afterCreate(): void
    {
        FieldColumnManager::createColumn($this->record);
    }
}
