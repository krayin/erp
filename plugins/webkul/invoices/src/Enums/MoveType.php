<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum MoveType: string implements HasLabel
{
    case ENTRY = 'entry';

    case OUT_INVOICE = 'out_invoice';

    case OUT_REFUND = 'out_refund';

    case IN_INVOICE = 'in_invoice';

    case IN_REFUND = 'in_refund';

    case OUT_RECEIPT = 'out_receipt';

    case IN_RECEIPT = 'in_receipt';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ENTRY => __('invoices::enums/move-type.entry'),
            self::OUT_INVOICE => __('invoices::enums/move-type.out-invoice'),
            self::OUT_REFUND => __('invoices::enums/move-type.out-refund'),
            self::IN_INVOICE => __('invoices::enums/move-type.in-invoice'),
            self::IN_REFUND => __('invoices::enums/move-type.in-refund'),
            self::OUT_RECEIPT => __('invoices::enums/move-type.out-receipt'),
            self::IN_RECEIPT => __('invoices::enums/move-type.in-receipt'),
        };
    }

    public static function options(): array
    {
        return [
            self::ENTRY->value => __('invoices::enums/move-type.entry'),
            self::OUT_INVOICE->value => __('invoices::enums/move-type.out-invoice'),
            self::OUT_REFUND->value => __('invoices::enums/move-type.out-refund'),
            self::IN_INVOICE->value => __('invoices::enums/move-type.in-invoice'),
            self::IN_REFUND->value => __('invoices::enums/move-type.in-refund'),
            self::OUT_RECEIPT->value => __('invoices::enums/move-type.out-receipt'),
            self::IN_RECEIPT->value => __('invoices::enums/move-type.in-receipt'),
        ];
    }
}
