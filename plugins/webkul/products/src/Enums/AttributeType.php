<?php

namespace Webkul\Product\Enums;

enum AttributeType: string
{
    case RADIO = 'radio';

    case SELECT = 'select';

    case COLOR = 'color';

    public static function options(): array
    {
        return [
            self::RADIO->value  => __('products::enums/attribute-type.radio'),
            self::SELECT->value => __('products::enums/attribute-type.select'),
            self::COLOR->value  => __('products::enums/attribute-type.color'),
        ];
    }
}
