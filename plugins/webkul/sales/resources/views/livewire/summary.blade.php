<div>
    <style>
        .invoice-container {
            width: 350px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
        }

        :is(.dark .invoice-container) {
            background-color: rgb(36 36 39);
            border: 1px solid rgb(44 44 47);
        }

        .invoice-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #555;
        }

        :is(.dark .invoice-item) {
            color: #d1d5db;
        }

        .invoice-item span {
            font-weight: 600;
        }

        .divider {
            border-bottom: 1px solid #ddd;
            margin: 12px 0;
        }

        :is(.dark .divider) {
            border-bottom-color: #374151;
        }

        :is(.dark .total) {
            background-color: rgba(255, 255, 255, 0.05);
            color: #f3f4f6;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 10px;
        }

        :is(.dark .footer) {
            color: #9ca3af;
        }
    </style>

    @if (count($products))
        <div class="flex justify-end">
            <div class="invoice-container">
                @php
                    $subtotal = 0;
                    $totalDiscount = 0;
                    $totalIncludedTax = 0;
                    $totalAdditionalTax = 0;
                    $grandTotal = 0;

                    foreach ($products as $product) {
                        $quantity = floatval($product['quantity'] ?? 0);
                        $price = floatval($product['price_unit'] ?? 0);
                        $discount = floatval($product['discount'] ?? 0);
                        $taxIds = $product['tax'] ?? [];

                        $lineBaseAmount = $quantity * $price;

                        $lineDiscountAmount = $lineBaseAmount * ($discount / 100);
                        $lineSubtotalBeforeTax = $lineBaseAmount - $lineDiscountAmount;

                        $totalDiscount += $lineDiscountAmount;

                        $lineIncludedTax = 0;
                        $lineAdditionalTax = 0;
                        $adjustedSubtotal = $lineSubtotalBeforeTax;

                        if (!empty($taxIds)) {
                            $taxes = \Webkul\Account\Models\Tax::whereIn('id', $taxIds)->get();

                            foreach ($taxes as $tax) {
                                $taxValue = floatval($tax->amount);
                                if ($tax->include_base_amount) {
                                    $includedTaxRate = $taxValue / 100;
                                    $includedTaxAmount = $adjustedSubtotal - ($adjustedSubtotal / (1 + $includedTaxRate));
                                    $lineIncludedTax += $includedTaxAmount;
                                    $adjustedSubtotal -= $includedTaxAmount;
                                }
                            }

                            foreach ($taxes as $tax) {
                                $taxValue = floatval($tax->amount);
                                if (!$tax->include_base_amount) {
                                    $lineAdditionalTax += $adjustedSubtotal * ($taxValue / 100);
                                }
                            }
                        }

                        $subtotal += $adjustedSubtotal;
                        $totalIncludedTax += $lineIncludedTax;
                        $totalAdditionalTax += $lineAdditionalTax;
                    }

                    $totalTax = $totalIncludedTax + $totalAdditionalTax;
                    $grandTotal = $subtotal + $totalAdditionalTax;
                @endphp

                <div class="invoice-item">
                    <span>Subtotal (Before Discount)</span>
                    <span>{{ number_format($subtotal + $totalDiscount, 2) }}</span>
                </div>

                @if ($totalDiscount > 0)
                    <div class="invoice-item">
                        <span>Discount</span>
                        <span>-{{ number_format($totalDiscount, 2) }}</span>
                    </div>
                @endif

                <div class="invoice-item">
                    <span>Subtotal (After Discount)</span>
                    <span>{{ number_format($subtotal, 2) }}</span>
                </div>

                @if ($totalIncludedTax > 0)
                    <div class="invoice-item">
                        <span>Included Tax</span>
                        <span>{{ number_format($totalIncludedTax, 2) }}</span>
                    </div>
                @endif

                @if ($totalAdditionalTax > 0)
                    <div class="invoice-item">
                        <span>Additional Tax</span>
                        <span>{{ number_format($totalAdditionalTax, 2) }}</span>
                    </div>
                @endif

                <div class="divider"></div>
                <div class="invoice-item font-bold">
                    <span>Grand Total</span>
                    <span>{{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
