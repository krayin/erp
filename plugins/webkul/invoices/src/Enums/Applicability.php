<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum Applicability: string implements HasLabel
{
    case ACCOUNT = 'percent';

    case TAXES = 'taxes';

    case PRODUCTS = 'products';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACCOUNT  => __('invoices::enums/applicability.account'),
            self::TAXES    => __('invoices::enums/applicability.taxes'),
            self::PRODUCTS => __('invoices::enums/applicability.products'),
        };
    }

    public static function options(): array
    {
        return [
            self::ACCOUNT->value => __('invoices::enums/applicability.account'),
            self::TAXES->value => __('invoices::enums/applicability.taxes'),
            self::PRODUCTS->value => __('invoices::enums/applicability.products'),
        ];
    }
}
