<?php

namespace Webkul\Product\Enums;

enum ProductType: string
{
    case GOODS = 'goods';

    case SERVICE = 'service';

    public static function options(): array
    {
        return [
            self::GOODS->value   => __('products::enums/product-type.goods'),
            self::SERVICE->value => __('products::enums/product-type.service'),
        ];
    }
}
