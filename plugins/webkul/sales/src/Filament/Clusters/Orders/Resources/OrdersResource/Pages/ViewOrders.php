<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Traits\HasQuotationAndOrderActions;

class ViewOrders extends ViewRecord
{
    use HasQuotationAndOrderActions;

    protected static string $resource = OrdersResource::class;
}
