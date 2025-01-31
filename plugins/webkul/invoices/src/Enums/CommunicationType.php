<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommunicationType: string implements HasLabel
{
    case NONE = 'open';

    case PARTNER = 'partner';

    case INVOICE = 'invoice';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NONE    => __('invoices::enums/communication-type.open'),
            self::PARTNER => __('invoices::enums/communication-type.partner'),
            self::INVOICE => __('invoices::enums/communication-type.invoice'),
        };
    }

    public static function options(): array
    {
        return [
            self::NONE->value    => __('invoices::enums/communication-type.open'),
            self::PARTNER->value => __('invoices::enums/communication-type.partner'),
            self::INVOICE->value => __('invoices::enums/communication-type.invoice'),
        ];
    }
}
