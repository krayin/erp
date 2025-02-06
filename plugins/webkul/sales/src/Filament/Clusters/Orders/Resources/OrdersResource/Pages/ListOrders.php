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
                ->icon('heroicon-s-document')
                ->favorite()
                ->default()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', Auth::id())),
            'to_invoice' => PresetView::make(__('To Invoice'))
                ->icon('heroicon-s-document')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('invoice_status', InvoiceStatus::TO_INVOICE->value)),
            'to_upselling' => PresetView::make(__('To Invoice'))
                ->icon('heroicon-s-document')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('invoice_status', InvoiceStatus::UPSELLING->value)),
        ];
    }
}
