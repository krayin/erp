<?php

namespace Webkul\Sale\Enums;

use Filament\Support\Contracts\HasLabel;

enum InvoiceStatus: string implements HasLabel
{
    case UP_SELLING = 'up_selling';

    case INVOICED = 'invoiced';

    case TO_INVOICE = 'to_invoice';

    case NO = 'no';

    public function getLabel(): string
    {
        return match ($this) {
            self::UP_SELLING   => __('sales::enums/invoice-status.up-selling'),
            self::INVOICED    => __('sales::enums/invoice-status.invoiced'),
            self::TO_INVOICE  => __('sales::enums/invoice-status.to-invoice'),
            self::NO          => __('sales::enums/invoice-status.no'),
        };
    }

    public static function options(): array
    {
        return [
            self::UP_SELLING->value   => __('sales::enums/invoice-status.up-selling'),
            self::INVOICED->value    => __('sales::enums/invoice-status.invoiced'),
            self::TO_INVOICE->value  => __('sales::enums/invoice-status.to-invoice'),
            self::NO->value          => __('sales::enums/invoice-status.no'),
        ];
    }
}
