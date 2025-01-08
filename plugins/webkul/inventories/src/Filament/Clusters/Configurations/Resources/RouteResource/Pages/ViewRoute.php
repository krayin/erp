<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoute extends ViewRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
