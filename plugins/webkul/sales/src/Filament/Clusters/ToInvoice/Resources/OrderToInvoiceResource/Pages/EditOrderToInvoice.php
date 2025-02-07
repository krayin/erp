<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Traits\HasSaleOrderActions;

class EditOrderToInvoice extends EditRecord
{
    use HasSaleOrderActions;

    protected static string $resource = OrderToInvoiceResource::class;
}
