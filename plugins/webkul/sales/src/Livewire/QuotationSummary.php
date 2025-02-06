<?php

namespace Webkul\Sale\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class QuotationSummary extends Component
{
    #[Reactive]
    public $products = [];

    public $subtotal = 0;
    public $totalDiscount = 0;
    public $totalTax = 0;
    public $grandTotal = 0;

    public function mount($products)
    {
        $this->products = $products ?? [];
    }

    public function render()
    {
        return view('sales::livewire/quotation-summary', [
            'products' => $this->products,
        ]);
    }
}
