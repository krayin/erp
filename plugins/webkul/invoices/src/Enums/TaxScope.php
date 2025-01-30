<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum TaxScope: string implements HasLabel
{
    case SERVICE = 'service';

    case CONSU = 'consu';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SERVICE => __('invoices::enums/tax-scope.service'),
            self::CONSU   => __('invoices::enums/tax-scope.consu'),
        };
    }

    public static function options(): array
    {
        return [
            self::SERVICE->value => __('invoices::enums/tax-scope.service'),
            self::SERVICE->value => __('invoices::enums/tax-scope.consu'),
        ];
    }
}
