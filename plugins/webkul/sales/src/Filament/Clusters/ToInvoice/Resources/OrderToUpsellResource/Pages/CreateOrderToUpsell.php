<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderToUpsell extends CreateRecord
{
    protected static string $resource = OrderToUpsellResource::class;
}
