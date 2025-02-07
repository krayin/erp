<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductTag extends CreateRecord
{
    protected static string $resource = ProductTagResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
