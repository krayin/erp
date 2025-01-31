<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum AmountType: string implements HasLabel
{
    case FIXED = 'fixed';

    case GROUP = 'group';

    case PERCENT = 'percent';

    case DIVISION = 'division';

    case CODE = 'code';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PERCENT  => __('invoices::enums/amount-type.percent'),
            self::FIXED    => __('invoices::enums/amount-type.fixed'),
            self::GROUP    => __('invoices::enums/amount-type.group'),
            self::DIVISION => __('invoices::enums/amount-type.division'),
            self::CODE     => __('invoices::enums/amount-type.code'),
        };
    }

    public static function options(): array
    {
        return [
            self::PERCENT->value  => __('invoices::enums/amount-type.percent'),
            self::FIXED->value    => __('invoices::enums/amount-type.fixed'),
            self::GROUP->value    => __('invoices::enums/amount-type.group'),
            self::DIVISION->value => __('invoices::enums/amount-type.division'),
            self::CODE->value     => __('invoices::enums/amount-type.code'),
        ];
    }
}
