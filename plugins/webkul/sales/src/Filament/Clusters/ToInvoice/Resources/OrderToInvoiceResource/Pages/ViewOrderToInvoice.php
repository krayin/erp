<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Traits\HasSaleOrderActions;
use Filament\Actions;

class ViewOrderToInvoice extends ViewRecord
{
    use HasSaleOrderActions;

    protected static string $resource = OrderToInvoiceResource::class;

    protected function getAdditionalHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
