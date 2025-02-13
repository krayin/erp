<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource;
use Filament\Actions;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
