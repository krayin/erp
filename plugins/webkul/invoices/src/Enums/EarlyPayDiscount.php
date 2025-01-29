<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum EarlyPayDiscount: string implements HasLabel
{
    case INCLUDED = 'on_early_payment';

    case EXCLUDED = 'never';

    case MIXED = 'always_upon_invoice';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INCLUDED => __('invoices::enums/early-pay-discount.included'),
            self::EXCLUDED => __('invoices::enums/early-pay-discount.excluded'),
            self::MIXED    => __('invoices::enums/early-pay-discount.mixed'),
        };
    }

    public static function options(): array
    {
        return [
            self::INCLUDED->value => __('invoices::enums/early-pay-discount.included'),
            self::EXCLUDED->value => __('invoices::enums/early-pay-discount.excluded'),
            self::MIXED->value    => __('invoices::enums/early-pay-discount.mixed'),
        ];
    }
}
