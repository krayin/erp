<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\TableViews\Filament\Concerns\HasTableViews;
use Webkul\TableViews\Filament\Components\PresetView;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Invoice\Enums\TaxScope;
use Webkul\Invoice\Enums\TypeTaxUse;

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
            'sale' => PresetView::make('Sale')
                ->icon('heroicon-o-scale')
                ->favorite()
                ->label(__('Sale'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type_tax_use', TypeTaxUse::SALE->value)),
            'purchase' => PresetView::make('Purchase')
                ->icon('heroicon-o-currency-dollar')
                ->favorite()
                ->label(__('Purchase'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type_tax_use', TypeTaxUse::PURCHASE->value)),
            'tax_scope' => PresetView::make('Tax Scope')
                ->icon('heroicon-o-magnifying-glass-circle')
                ->favorite()
                ->label(__('Tax Scope'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tax_scope', TaxScope::SERVICE->value)),
            'goods' => PresetView::make('Goods')
                ->icon('heroicon-o-check')
                ->favorite()
                ->label(__('Tax Scope'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('tax_scope', TaxScope::CONSU->value)),
            'active' => PresetView::make('Active')
                ->icon('heroicon-o-check-circle')
                ->favorite()
                ->label(__('Active'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', true)),
            'in_active' => PresetView::make('In active')
                ->icon('heroicon-o-x-circle')
                ->favorite()
                ->label(__('In Active'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', false)),
        ];
    }
}
