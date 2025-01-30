<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentType: string implements HasLabel
{
    case INVOICE = 'invoice';

    case REFUND = 'refund';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INVOICE => __('invoices::enums/document-type.invoice'),
            self::REFUND   => __('invoices::enums/document-type.refund'),
        };
    }

    public static function options(): array
    {
        return [
            self::INVOICE->value => __('invoices::enums/document-type.invoice'),
            self::REFUND->value => __('invoices::enums/document-type.refund'),
        ];
    }
}
