<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderToInvoice extends ViewRecord
{
    protected static string $resource = OrderToInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
