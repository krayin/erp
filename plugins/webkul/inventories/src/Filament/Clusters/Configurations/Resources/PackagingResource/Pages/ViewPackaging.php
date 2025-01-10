<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackagingResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackagingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPackaging extends ViewRecord
{
    protected static string $resource = PackagingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
