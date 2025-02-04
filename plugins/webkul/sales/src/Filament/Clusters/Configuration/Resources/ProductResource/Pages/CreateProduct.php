<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
