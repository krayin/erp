<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages\EditQuotation as BaseEditOrderQuotation;

class EditOrderToInvoice extends BaseEditOrderQuotation
{
    protected static string $resource = OrderToInvoiceResource::class;
}
