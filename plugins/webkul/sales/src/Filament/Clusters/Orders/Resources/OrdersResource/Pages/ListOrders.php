<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ListOrders extends ListRecords
{
    use HasTableViews;

    protected static string $resource = OrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'my_orders' => PresetView::make(__('My Orders'))
                ->icon('heroicon-s-shopping-bag')
                ->favorite()
                ->default()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', Auth::id())),
            'to_invoice' => PresetView::make(__('To Invoice'))
                ->icon('heroicon-s-receipt-percent')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('invoice_status', InvoiceStatus::TO_INVOICE->value)),
            'up_selling' => PresetView::make(__('Up Selling'))
                ->icon('heroicon-s-receipt-refund')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('invoice_status', InvoiceStatus::UP_SELLING->value)),
        ];
    }
}
