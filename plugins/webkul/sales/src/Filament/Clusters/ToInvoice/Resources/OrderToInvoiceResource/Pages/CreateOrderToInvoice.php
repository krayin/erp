<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderToInvoice extends CreateRecord
{
    protected static string $resource = OrderToInvoiceResource::class;
}
