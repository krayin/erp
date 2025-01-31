<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum JournalType: string implements HasLabel
{
    case SALE        = 'tax_included';
    case PURCHASE    = 'tax_excluded';
    case CASH        = 'cash';
    case BANK        = 'bank';
    case CREDIT_CARD = 'credit';
    case GENERAL     = 'general';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SALE => __('invoices::enums/journal-type.sale'),
            self::PURCHASE => __('invoices::enums/journal-type.purchase'),
            self::CASH => __('invoices::enums/journal-type.cash'),
            self::BANK => __('invoices::enums/journal-type.bank'),
            self::CREDIT_CARD => __('invoices::enums/journal-type.credit'),
            self::GENERAL => __('invoices::enums/journal-type.general'),
        };
    }

    public static function options(): array
    {
        return [
            self::SALE->value => __('invoices::enums/journal-type.sale'),
            self::PURCHASE->value => __('invoices::enums/journal-type.purchase'),
            self::CASH->value => __('invoices::enums/journal-type.cash'),
            self::BANK->value => __('invoices::enums/journal-type.bank'),
            self::CREDIT_CARD->value => __('invoices::enums/journal-type.credit'),
            self::GENERAL->value => __('invoices::enums/journal-type.general'),
        ];
    }
}
