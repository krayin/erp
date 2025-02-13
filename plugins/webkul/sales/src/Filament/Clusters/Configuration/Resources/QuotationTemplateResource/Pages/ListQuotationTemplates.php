<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuotationTemplates extends ListRecords
{
    protected static string $resource = QuotationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
