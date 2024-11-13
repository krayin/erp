<?php

namespace Webkul\Chatter\Filament\Resources\NoteResource\Pages;

use Webkul\Chatter\Filament\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;
}
