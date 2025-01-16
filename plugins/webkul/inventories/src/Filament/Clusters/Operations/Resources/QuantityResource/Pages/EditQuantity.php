<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\QuantityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\QuantityResource;

class EditQuantity extends EditRecord
{
    protected static string $resource = QuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
