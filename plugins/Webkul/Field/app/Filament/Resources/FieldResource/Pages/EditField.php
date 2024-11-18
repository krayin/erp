<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Webkul\Field\Filament\Resources\FieldResource;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Field\FieldColumnManager;

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
