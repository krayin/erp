<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Traits\HasQuotationAndOrderActions;

class ViewOrderToUpsell extends ViewRecord
{
    use HasQuotationAndOrderActions;

    protected static string $resource = OrderToUpsellResource::class;
}
