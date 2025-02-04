<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductTag extends EditRecord
{
    protected static string $resource = ProductTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
