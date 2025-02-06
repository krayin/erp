<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Traits\HasQuotationAndOrderActions;

class EditOrderToUpsell extends EditRecord
{
    use HasQuotationAndOrderActions;

    protected static string $resource = OrderToUpsellResource::class;
}
