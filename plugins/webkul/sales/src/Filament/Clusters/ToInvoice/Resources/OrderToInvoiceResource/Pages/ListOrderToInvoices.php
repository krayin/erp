<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Concerns\HasTableViews;
use Webkul\TableViews\Filament\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListOrderToInvoices extends ListRecords
{
    use HasTableViews;

    protected static string $resource = OrderToInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getPresetTableViews(): array
    {
        return [
            'my_orders' => PresetView::make(__('My Orders'))
                ->icon('heroicon-s-shopping-bag')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', Auth::id())),
            'archived' => PresetView::make(__('Archived'))
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(fn($query) => $query->onlyTrashed()),
        ];
    }
}
