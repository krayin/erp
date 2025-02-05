<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrders extends CreateRecord
{
    protected static string $resource = OrdersResource::class;
}
