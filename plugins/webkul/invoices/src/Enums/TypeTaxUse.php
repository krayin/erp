<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum TypeTaxUse: string implements HasLabel
{
    case SALE = 'sale';

    case PURCHASE = 'purchase';

    case NONE = 'none';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SALE     => __('invoices::enums/type-tax-use.sale'),
            self::PURCHASE => __('invoices::enums/type-tax-use.purchase'),
            self::NONE     => __('invoices::enums/type-tax-use.none'),
        };
    }

    public static function options(): array
    {
        return [
            self::SALE->value     => __('invoices::enums/type-tax-use.sale'),
            self::PURCHASE->value => __('invoices::enums/type-tax-use.purchase'),
            self::NONE->value     => __('invoices::enums/type-tax-use.none'),
        ];
    }
}
