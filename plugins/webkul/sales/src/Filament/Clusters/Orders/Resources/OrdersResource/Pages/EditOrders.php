<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Traits\HasQuotationAndOrderActions;

class EditOrders extends EditRecord
{
    use HasQuotationAndOrderActions;

    protected static string $resource = OrdersResource::class;
}
