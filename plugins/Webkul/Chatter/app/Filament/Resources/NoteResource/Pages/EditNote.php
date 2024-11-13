<?php

namespace Webkul\Chatter\Filament\Resources\NoteResource\Pages;

use Webkul\Chatter\Filament\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNote extends EditRecord
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
