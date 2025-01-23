<?php

namespace Webkul\Product\Enums;

use Filament\Support\Contracts\HasLabel;

enum PriceRuleBase: string implements HasLabel
{
    case LIST_PRICE = 'list_price';

    case STANDARD_PRICE = 'standard_price';

    case PRICE_RULES = 'price_rules';

    public function getLabel(): string
    {
        return match ($this) {
            self::LIST_PRICE     => __('products::enums/price-rule-base.list-price'),
            self::STANDARD_PRICE => __('products::enums/price-rule-base.standard-price'),
            self::PRICE_RULES    => __('products::enums/price-rule-base.price-rules'),
        };
    }
}
