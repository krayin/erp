<?php

namespace Webkul\Chatter\Filament\Resources\FollowerResource\Pages;

use Webkul\Chatter\Filament\Resources\FollowerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowers extends ListRecords
{
    protected static string $resource = FollowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
