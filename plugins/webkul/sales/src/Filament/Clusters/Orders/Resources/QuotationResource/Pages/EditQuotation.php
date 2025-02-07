<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sale\Traits\HasSaleOrderActions;
use Webkul\Account\Models\Tax;

class EditQuotation extends EditRecord
{
    use HasSaleOrderActions;

    protected static string $resource = QuotationResource::class;

    protected function getRedirectUrl(): string
    {

        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);

        $record = $this->getRecord();
        $orderSales = $record->orderSales;

        if ($orderSales->isEmpty()) {
            return $record;
        }

        $taxIds = $orderSales->flatMap(fn($sale) => $sale->product?->productTaxes->pluck('id') ?? [])->unique()->toArray();
        $taxData = Tax::whereIn('id', $taxIds)->get()->keyBy('id');

        $totals = $orderSales->reduce(function ($carry, $orderSale) use ($taxData) {
            $quantity = (float) ($orderSale->product_uom_qty ?? 0);
            $price = (float) ($orderSale->price_unit ?? 0);
            $taxIds = $orderSale->product?->productTaxes->pluck('id')->toArray() ?? [];

            $lineSubtotal = $quantity * $price;
            $adjustedSubtotal = $lineSubtotal;
            $lineTax = 0;

            foreach ($taxIds as $taxId) {
                if (! isset($taxData[$taxId])) {
                    continue;
                }

                $tax = $taxData[$taxId];
                $taxValue = (float) $tax->amount;

                if ($tax->include_base_amount) {
                    $baseSubtotal = $adjustedSubtotal / (1 + ($taxValue / 100));
                    $lineTax += $adjustedSubtotal - $baseSubtotal;
                    $adjustedSubtotal = $baseSubtotal;
                } else {
                    $lineTax += $adjustedSubtotal * ($taxValue / 100);
                }
            }

            return [
                'subtotal' => $carry['subtotal'] + $adjustedSubtotal,
                'totalTax' => $carry['totalTax'] + $lineTax,
            ];
        }, ['subtotal' => 0, 'totalTax' => 0]);

        $record->update([
            'amount_untaxed' => $totals['subtotal'],
            'amount_tax'     => $totals['totalTax'],
            'amount_total'   => $totals['subtotal'] + $totals['totalTax'],
        ]);

        return $record;
    }
}
