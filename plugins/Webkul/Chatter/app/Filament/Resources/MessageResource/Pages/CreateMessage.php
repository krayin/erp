<?php

namespace Webkul\Chatter\Filament\Resources\MessageResource\Pages;

use Webkul\Chatter\Filament\Resources\MessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMessage extends CreateRecord
{
    protected static string $resource = MessageResource::class;
}
