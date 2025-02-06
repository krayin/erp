<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Traits\HasQuotationAndOrderActions;

class ViewOrderToInvoice extends ViewRecord
{
    use HasQuotationAndOrderActions;

    protected static string $resource = OrderToInvoiceResource::class;
}
