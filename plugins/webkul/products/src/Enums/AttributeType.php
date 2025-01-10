<?php

namespace Webkul\Product\Enums;

use Filament\Support\Contracts\HasLabel;

enum AttributeType: string implements HasLabel
{
    case RADIO = 'radio';

    case SELECT = 'select';

    case COLOR = 'color';

    public function getLabel(): string
    {
        return match ($this) {
            self::RADIO  => __('products::enums/attribute-type.radio'),
            self::SELECT => __('products::enums/attribute-type.select'),
            self::COLOR  => __('products::enums/attribute-type.color'),
        };
    }
}
