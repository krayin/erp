<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPickingType extends ViewRecord
{
    protected static string $resource = PickingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
