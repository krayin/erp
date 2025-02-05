<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrders extends ViewRecord
{
    protected static string $resource = OrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
