<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Concerns\HasTableViews;
use Webkul\TableViews\Filament\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Account\Enums;

class ListTaxes extends ListRecords
{
    use HasTableViews;

    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getPresetTableViews(): array
    {
        return [
            'sale' => PresetView::make('sale')
                ->icon('heroicon-o-scale')
                ->favorite()
                ->label(__('invoices::filament/clusters/configurations/resources/tax/pages/list-tax.tabs.sale'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type_tax_use', Enums\TypeTaxUse::SALE->value)),
            'purchase' => PresetView::make('purchase')
                ->icon('heroicon-o-currency-dollar')
                ->favorite()
                ->label(__('invoices::filament/clusters/configurations/resources/tax/pages/list-tax.tabs.purchase'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type_tax_use', Enums\TypeTaxUse::PURCHASE->value)),
            'tax_scope' => PresetView::make('tax_scope')
                ->icon('heroicon-o-magnifying-glass-circle')
                ->favorite()
                ->label(__('invoices::filament/clusters/configurations/resources/tax/pages/list-tax.tabs.tax-scope'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tax_scope', Enums\TaxScope::SERVICE->value)),
            'goods' => PresetView::make('goods')
                ->icon('heroicon-o-check')
                ->favorite()
                ->label(__('invoices::filament/clusters/configurations/resources/tax/pages/list-tax.tabs.goods'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tax_scope', Enums\TaxScope::CONSU->value)),
            'active' => PresetView::make('Active')
                ->icon('heroicon-o-check-circle')
                ->favorite()
                ->label(__('invoices::filament/clusters/configurations/resources/tax/pages/list-tax.tabs.active'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', true)),
            'in_active' => PresetView::make('In active')
                ->icon('heroicon-o-x-circle')
                ->favorite()
                ->label(__('invoices::filament/clusters/configurations/resources/tax/pages/list-tax.tabs.in-active'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', false)),
        ];
    }
}
