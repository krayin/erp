<?php

namespace Webkul\Product\Enums;

use Filament\Support\Contracts\HasLabel;

enum PriceRuleType: string implements HasLabel
{
    case PERCENTAGE = 'percentage';

    case FORMULA = 'formula';

    case FIXED = 'fixed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PERCENTAGE   => __('products::enums/price-rule-type.percentage'),
            self::FORMULA      => __('products::enums/price-rule-type.formula'),
            self::FIXED        => __('products::enums/price-rule-type.fixed'),
        };
    }
}
