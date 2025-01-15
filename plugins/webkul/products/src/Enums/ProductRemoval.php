<?php

namespace Webkul\Product\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductRemoval: string implements HasLabel
{
    case FIFO = 'fifo';

    case LIFO = 'lifo';

    case CLOSEST = 'closest';

    case LEAST_PACKAGES = 'least_packages';

    case FEFO = 'fefo';

    public function getLabel(): string
    {
        return match ($this) {
            self::FIFO             => __('products::enums/product-removal.fifo'),
            self::LIFO             => __('products::enums/product-removal.lifo'),
            self::CLOSEST          => __('products::enums/product-removal.closest'),
            self::LEAST_PACKAGES   => __('products::enums/product-removal.least-packages'),
            self::FEFO             => __('products::enums/product-removal.fefo'),
        };
    }
}
