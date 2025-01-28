<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuotationTemplate extends EditRecord
{
    protected static string $resource = QuotationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
