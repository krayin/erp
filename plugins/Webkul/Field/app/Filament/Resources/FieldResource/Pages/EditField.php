<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Webkul\Field\Filament\Resources\FieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditField extends EditRecord
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
