<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\QuantityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\QuantityResource;

class ListQuantities extends ListRecords
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
