<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\FiscalPositionResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\FiscalPositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFiscalPositions extends ListRecords
{
    protected static string $resource = FiscalPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
