<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;

use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLot extends EditRecord
{
    protected static string $resource = LotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
