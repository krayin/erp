<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuotationTemplate extends CreateRecord
{
    protected static string $resource = QuotationTemplateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
