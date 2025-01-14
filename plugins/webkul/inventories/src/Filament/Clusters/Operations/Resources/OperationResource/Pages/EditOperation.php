<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource\Pages;

use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOperation extends EditRecord
{
    protected static string $resource = OperationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
