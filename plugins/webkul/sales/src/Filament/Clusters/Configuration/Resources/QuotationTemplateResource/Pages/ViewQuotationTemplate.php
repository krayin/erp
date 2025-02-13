<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource;
use Filament\Actions;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\ViewRecord;

class ViewQuotationTemplate extends ViewRecord
{
    protected static string $resource = QuotationTemplateResource::class;

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
