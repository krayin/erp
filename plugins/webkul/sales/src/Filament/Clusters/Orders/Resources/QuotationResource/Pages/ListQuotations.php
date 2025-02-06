<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Concerns\HasTableViews;
use Webkul\TableViews\Filament\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Enums\OrderState;

class ListQuotations extends ListRecords
{
    use HasTableViews;

    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'my_quotations' => PresetView::make(__('My Quotations'))
                ->icon('heroicon-s-document')
                ->favorite()
                ->default()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', Auth::id())),
            'quotations' => PresetView::make(__('Quotations'))
                ->icon('heroicon-s-document')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('state', [OrderState::DRAFT->value, OrderState::SENT->value])),
            'sale_orders' => PresetView::make(__('Sales Orders'))
                ->icon('heroicon-s-shopping-bag')
                ->favorite()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('state', OrderState::SALE->value)),
        ];
    }
}
