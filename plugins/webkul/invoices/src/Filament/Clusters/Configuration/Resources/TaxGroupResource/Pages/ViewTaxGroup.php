<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTaxGroup extends ViewRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
