<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum AutoPost: string implements HasLabel
{
    case NO = 'no';

    case AT_DATE = 'at_date';

    case MONTHLY = 'monthly';

    case QUARTERLY = 'quarterly';

    case YEARLY = 'yearly';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NO    => __('invoices::enums/auto-post.no'),
            self::AT_DATE => __('invoices::enums/auto-post.at-date'),
            self::MONTHLY => __('invoices::enums/auto-post.monthly'),
            self::QUARTERLY => __('invoices::enums/auto-post.quarterly'),
            self::YEARLY => __('invoices::enums/auto-post.yearly'),
        };
    }

    public static function options(): array
    {
        return [
            self::NO->value    => __('invoices::enums/auto-post.no'),
            self::AT_DATE->value => __('invoices::enums/auto-post.at-date'),
            self::MONTHLY->value => __('invoices::enums/auto-post.monthly'),
            self::QUARTERLY->value => __('invoices::enums/auto-post.quarterly'),
            self::YEARLY->value => __('invoices::enums/auto-post.yearly'),
        ];
    }
}
