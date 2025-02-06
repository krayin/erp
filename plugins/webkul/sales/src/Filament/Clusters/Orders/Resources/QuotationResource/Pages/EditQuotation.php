<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Resources\Pages\EditRecord;
use Webkul\Sale\Traits\HasQuotationActions;

class EditQuotation extends EditRecord
{
    use HasQuotationActions;

    protected static string $resource = QuotationResource::class;

    protected function getRedirectUrl(): string
    {

        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
