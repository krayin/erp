<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Enums\OrderState;
use Webkul\Account\Models\Tax;
use Webkul\Sale\Enums\InvoiceStatus;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        return array_merge($data, [
            'creator_id'     => $user->id,
            'user_id'        => $user->id,
            'company_id'     => $user->default_company_id,
            'state'          => OrderState::DRAFT->value,
            'invoice_status' => InvoiceStatus::NO->value,
        ]);
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $salesOrderLines = $record->salesOrderLines;

        if ($salesOrderLines->isEmpty()) {
            return;
        }

        $taxIds = $salesOrderLines->flatMap(fn($sale) => $sale->product?->productTaxes->pluck('id') ?? [])->unique()->toArray();
        $taxData = Tax::whereIn('id', $taxIds)->get()->keyBy('id');

        $totals = $salesOrderLines->reduce(function ($carry, $orderSale) use ($taxData) {
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
    }
}
