<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Traits\HasSaleOrderActions;

class ViewOrderToUpsell extends ViewRecord
{
    use HasSaleOrderActions;

    protected static string $resource = OrderToUpsellResource::class;
}
