<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Traits\HasSaleOrderActions;

class EditOrders extends EditRecord
{
    use HasSaleOrderActions;

    protected static string $resource = OrdersResource::class;
}
