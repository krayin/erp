<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Traits\HasSaleOrderActions;
use Filament\Actions;

class EditOrderToUpsell extends EditRecord
{
    use HasSaleOrderActions;

    protected static string $resource = OrderToUpsellResource::class;

    protected function getAdditionalHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
