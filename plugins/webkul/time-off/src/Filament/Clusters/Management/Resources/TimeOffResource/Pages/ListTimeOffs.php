<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;

class ListTimeOffs extends ListRecords
{
    protected static string $resource = TimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
