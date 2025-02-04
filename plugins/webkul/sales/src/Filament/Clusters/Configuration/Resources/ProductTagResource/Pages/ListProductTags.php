<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductTags extends ListRecords
{
    protected static string $resource = ProductTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
