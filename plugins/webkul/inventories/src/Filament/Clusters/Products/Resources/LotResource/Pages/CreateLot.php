<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;

use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLot extends CreateRecord
{
    protected static string $resource = LotResource::class;
}
