<style>
.container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.header {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

@media (min-width: 640px) {
  .header {
    flex-direction: row;
    gap: 2rem;
  }
}

@media (min-width: 768px) { .header { gap: 4rem; } }
@media (min-width: 1024px) { .header { gap: 12rem; } }
@media (min-width: 1280px) { .header { gap: 15rem; } }

.company-info {
  width: 100%;
}

.company-logo {
  width: 4rem;
}

.label-text {
  font-size: 0.875rem;
  margin-top: 0.75rem;
}

.company-name {
  font-size: 1.125rem;
  font-weight: 700;
}

.contact-text {
  font-size: 0.875rem;
}

.partner-info {
  margin-top: 1.5rem;
}

.quotation-header {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  font-weight: 700;
}

.quotation-title {
  font-size: 1.875rem;
  text-transform: uppercase;
}

.items-table-container {
  padding: 0.5rem;
  margin: 1rem 0;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
}

.items-table {
  width: 100%;
  border-collapse: collapse;
}

.table-header {
  font-weight: 700;
  border-bottom: 1px solid #e5e7eb;
  text-align: left;
}

.table-cell {
  padding: 0.5rem 1rem;
  text-align: center;
  width: 25%;
}

.item-name {
  font-size: 1.125rem;
  font-weight: 700;
}

.item-description {
  color: #9ca3af;
}

.footer {
  display: flex;
  justify-content: space-between;
  margin-top: 1.5rem;
}

.signature-section {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.totals-section {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 1rem;
}

.total-row {
  display: flex;
  justify-content: space-between;
}

.total-border {
  padding-bottom: 1rem;
  border-bottom: 1px solid #e5e7eb;
}

.grand-total {
  font-size: 1.25rem;
  font-weight: 700;
}

.section-title {
  font-size: 1.25rem;
  margin-bottom: 0.5rem;
}

.divider {
  margin: 1rem 0;
  border-bottom: 1px solid #e5e7eb;
}

.dark .items-table-container { border-color: #374151; }
.dark .table-header { border-color: #374151; }
.dark .total-border { border-color: #374151; }
.dark .divider { border-color: #374151; }
</style>

<div class="container">
    <div class="header">
        <div class="company-info">
            <div>
                <img src="{{ asset('storage/'.$record->company?->partner?->avatar) ?? '' }}" alt="{{ $record->company->name }}" class="company-logo">
            </div>
            <div>
                <div class="label-text">Bill From:</div>
                <div class="company-name">{{ $record->company->name }}</div>
                <div class="contact-text">{{ $record->company->phone }}</div>
                <div class="contact-text">
                    {{ sprintf(
                        "%s\n%s%s\n%s, %s %s\n%s",
                        $record->company->address->name ?? '',
                        $record->company->address->street1 ?? '',
                        $record->company->address->street2 ? ', ' . $record->company->address->street2 : '',
                        $record->company->address->city ?? '',
                        $record->company->address->state ? $record->company->address->state->name : '',
                        $record->company->address->zip ?? '',
                        $record->company->address->country ? $record->company->address->country->name : ''
                    ) }}
                </div>
                <div class="contact-text">{{ $record->company->city }}</div>
                <div class="contact-text">{{ $record->company->country }}</div>
            </div>

            <div class="partner-info">
                <div class="label-text">Bill To:</div>
                <div class="company-name">{{ $record->partner->name }}</div>
                <div class="contact-text">{{ $record->partner->email }}</div>
                <div class="contact-text">{{ $record->partner->phone }}</div>
                <div class="contact-text">
                    {{ sprintf(
                        "%s\n%s%s\n%s %s %s\n%s",
                        $record->partner->address?->name ?? '',
                        $record->partner->address?->street1 ?? '',
                        $record->partner->address?->street2 ? ', ' . $record->partner->address?->street2 : '',
                        $record->partner->address?->city ?? '',
                        $record->partner->address?->state ? $record->partner->address?->state->name : '',
                        $record->partner->address?->zip ?? '',
                        $record->partner->address?->country ? $record->partner->address?->country->name : ''
                    ) }}
                </div>
            </div>
        </div>

        <div class="quotation-header">
            <div>
                <div class="quotation-title">Quotation</div>
                <div>#{{ $record->name }}</div>
            </div>
        </div>
    </div>

    <div class="items-table-container">
        <table class="items-table">
            <thead>
                <tr class="table-header">
                    <th class="table-cell">Item</th>
                    <th class="table-cell">Quantity</th>
                    <th class="table-cell">Price ({{ $record->currency->symbol }})</th>
                    <th class="table-cell">Tax (%)</th>
                    <th class="table-cell">Total ({{ $record->currency->symbol }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->salesOrderLines as $item)
                    <tr>
                        <td class="table-cell">
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-description">{{ $item->description }}</div>
                        </td>
                        <td class="table-cell">
                            <span class="company-name">{{ $item->product_uom_qty }}</span>
                        </td>
                        <td class="table-cell">
                            <span class="company-name">{{ number_format($item->price_unit, 2) }}</span>
                        </td>
                        <td class="table-cell">
                            <span class="company-name">
                                {{ implode(', ', $item->product?->productTaxes->pluck('name')->toArray() ?? []) }}
                            </span>
                        </td>
                        <td class="table-cell">
                            <span class="company-name">{{ number_format($item->price_total, 2) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="signature-section">
            <div>
                <div class="section-title">Signature</div>
                <div class="contact-text">
                    <div>{{ $record->company->name }}</div>
                    <div>{{ $record->company->email }}</div>
                    <div>{{ $record->company->phone }}</div>
                </div>
            </div>
        </div>

        <div class="totals-section">
            <div class="total-row">
                <div class="company-name">Subtotal</div>
                <div>
                    {{ number_format($record?->amount_untaxed, 2) }}
                    <small class="contact-text">({{ $record->currency->symbol }})</small>
                </div>
            </div>
            <div class="total-row total-border">
                <div class="company-name">Tax</div>
                <div>
                    {{ number_format($record?->amount_tax, 2) }}
                    <small class="contact-text">({{ $record->currency->symbol }})</small>
                </div>
            </div>
            <div class="total-row grand-total">
                <div>Grand Total</div>
                <div>
                    {{ number_format($record?->amount_total, 2) }}
                    <small class="contact-text">({{ $record->currency->symbol }})</small>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>
    <div>
        <div class="section-title">Payment Terms</div>
        <div class="contact-text">{{ $record->paymentTerm->name }}</div>
    </div>
</div>
