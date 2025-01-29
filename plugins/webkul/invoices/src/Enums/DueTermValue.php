<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum DueTermValue: string implements HasLabel
{
    case PERCENT = 'percent';

    case FIXED = 'fixed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PERCENT => __('invoices::enums/due-term-value.percent'),
            self::FIXED   => __('invoices::enums/due-term-value.fixed'),
        };
    }

    public static function options(): array
    {
        return [
            self::PERCENT->value => __('invoices::enums/due-term-value.percent'),
            self::FIXED->value   => __('invoices::enums/due-term-value.fixed'),
        ];
    }
}
