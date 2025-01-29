<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankAccounts extends ListRecords
{
    protected static string $resource = BankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
