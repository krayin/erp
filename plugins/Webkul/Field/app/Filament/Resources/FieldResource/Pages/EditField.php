<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Field\FieldColumnManager;
use Webkul\Field\Filament\Resources\FieldResource;

class EditField extends EditRecord
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        FieldColumnManager::updateColumn($this->record);
    }
}
