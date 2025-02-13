<?php

namespace Webkul\Sale\Enums;

use Filament\Support\Contracts\HasLabel;

enum InvoicePolicy: string implements HasLabel
{
    case ORDER = 'order';

    case DELIVERY = 'delivery';

    public function getLabel(): string
    {
        return match ($this) {
            self::ORDER       => __('sales::enums/invoice-policy.order'),
            self::DELIVERY    => __('sales::enums/invoice-policy.delivery'),
        };
    }

    public function options(): array
    {
        return [
            self::ORDER->value       => __('sales::enums/invoice-policy.order'),
            self::DELIVERY->value    => __('sales::enums/invoice-policy.delivery'),
        ];
    }
}
