<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
