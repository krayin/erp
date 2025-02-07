<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages\CreateQuotation as BaseCreateOrders;

class CreateOrders extends BaseCreateOrders
{
    protected static string $resource = OrdersResource::class;
}
