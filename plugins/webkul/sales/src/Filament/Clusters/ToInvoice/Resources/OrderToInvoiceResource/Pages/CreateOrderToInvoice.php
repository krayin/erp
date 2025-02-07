<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages\CreateQuotation as BaseCreateOrderToInvoice;

class CreateOrderToInvoice extends BaseCreateOrderToInvoice
{
    protected static string $resource = OrderToInvoiceResource::class;
}
