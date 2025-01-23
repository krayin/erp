<?php

namespace Webkul\Product\Enums;

use Filament\Support\Contracts\HasLabel;

enum PriceRuleApplyTo: string implements HasLabel
{
    case PRODUCT = 'product';

    case CATEGORY = 'category';

    public function getLabel(): string
    {
        return match ($this) {
            self::PRODUCT   => __('products::enums/price-rule-apply-to.product'),
            self::CATEGORY  => __('products::enums/price-rule-apply-to.category'),
        };
    }
}
