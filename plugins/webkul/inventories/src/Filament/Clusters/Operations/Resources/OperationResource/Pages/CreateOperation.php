<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource\Pages;

use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOperation extends CreateRecord
{
    protected static string $resource = OperationResource::class;
}
