<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ViewMyTimeOff extends ListRecords
{
    protected static string $resource = MyTimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
