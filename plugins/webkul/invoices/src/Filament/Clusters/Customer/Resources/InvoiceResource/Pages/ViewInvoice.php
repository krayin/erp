<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
