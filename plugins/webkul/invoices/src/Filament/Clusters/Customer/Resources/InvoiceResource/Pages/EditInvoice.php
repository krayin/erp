<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
