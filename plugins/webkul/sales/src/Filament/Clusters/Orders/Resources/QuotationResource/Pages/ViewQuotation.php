<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Traits\HasQuotationAndOrderActions;

class ViewQuotation extends ViewRecord
{
    use HasQuotationAndOrderActions;

    protected static string $resource = QuotationResource::class;
}
