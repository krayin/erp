<?php

namespace Webkul\Warehouse\Enums;

use Filament\Support\Contracts\HasLabel;

enum AllowNewProduct: string implements HasLabel
{
    case EMPTY = 'empty';

    case SAME = 'same';

    case MIXED = 'mixed';

    public function getLabel(): string
    {
        return match ($this) {
            self::EMPTY       => __('warehouses::enums/allow-new-product.empty'),
            self::SAME        => __('warehouses::enums/allow-new-product.same'),
            self::MIXED       => __('warehouses::enums/allow-new-product.mixed'),
        };
    }
}
