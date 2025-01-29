<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Webkul\Invoice\Models\PaymentTerm;

class ListPaymentTerms extends ListRecords
{
    protected static string $resource = PaymentTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('invoices::filament/clusters/configurations/resources/payment-term/pages/list-payment-term.tabs.all'))
                ->badge(PaymentTerm::count()),
            'archived' => Tab::make(__('invoices::filament/clusters/configurations/resources/payment-term/pages/list-payment-term.tabs.archived'))
                ->badge(PaymentTerm::onlyTrashed()->count())
                ->modifyQueryUsing(fn($query) => $query->onlyTrashed()),
        ];
    }
}
