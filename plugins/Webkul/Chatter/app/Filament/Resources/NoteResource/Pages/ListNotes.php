<?php

namespace Webkul\Chatter\Filament\Resources\NoteResource\Pages;

use Webkul\Chatter\Filament\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
