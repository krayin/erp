<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaxGroup extends EditRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
