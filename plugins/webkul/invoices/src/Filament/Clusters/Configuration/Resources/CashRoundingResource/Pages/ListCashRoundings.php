<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\CashRoundingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashRoundings extends ListRecords
{
    protected static string $resource = CashRoundingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
