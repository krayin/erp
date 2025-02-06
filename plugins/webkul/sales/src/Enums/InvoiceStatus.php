<?php

namespace Webkul\Sale\Enums;

use Filament\Support\Contracts\HasLabel;

enum InvoiceStatus: string implements HasLabel
{
    case UPSELLING = 'upselling';

    case INVOICED = 'invoiced';

    case TO_INVOICE = 'to_invoice';

    case NO = 'no';

    public function getLabel(): string
    {
        return match ($this) {
            self::UPSELLING   => __('sales::enums/invoice-status.upselling'),
            self::INVOICED    => __('sales::enums/invoice-status.invoiced'),
            self::TO_INVOICE  => __('sales::enums/invoice-status.to-invoice'),
            self::NO          => __('sales::enums/invoice-status.no'),
        };
    }

    public static function options(): array
    {
        return [
            self::UPSELLING->value   => __('sales::enums/invoice-status.upselling'),
            self::INVOICED->value    => __('sales::enums/invoice-status.invoiced'),
            self::TO_INVOICE->value  => __('sales::enums/invoice-status.to-invoice'),
            self::NO->value          => __('sales::enums/invoice-status.no'),
        ];
    }
}
