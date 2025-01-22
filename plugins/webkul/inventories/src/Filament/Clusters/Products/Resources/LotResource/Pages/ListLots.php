<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;

use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLots extends ListRecords
{
    protected static string $resource = LotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
