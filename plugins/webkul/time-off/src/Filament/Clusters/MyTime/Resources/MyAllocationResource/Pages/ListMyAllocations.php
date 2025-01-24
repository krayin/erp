<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource\Pages;

use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyAllocations extends ListRecords
{
    protected static string $resource = MyAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
