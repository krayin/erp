<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource;

class ListInternals extends ListRecords
{
    protected static string $resource = InternalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
