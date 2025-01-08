<?php

namespace Webkul\Warehouse\Enums;

use Filament\Support\Contracts\HasLabel;

enum LocationType: string implements HasLabel
{
    case SUPPLIER = 'supplier';

    case VIEW = 'view';

    case INTERNAL = 'internal';

    case CUSTOMER = 'customer';

    case INVENTORY = 'inventory';

    case PRODUCTION = 'production';

    case TRANSIT = 'transit';

    public function getLabel(): string
    {
        return match ($this) {
            self::SUPPLIER    => __('warehouses::enums/location-type.supplier'),
            self::VIEW        => __('warehouses::enums/location-type.view'),
            self::INTERNAL    => __('warehouses::enums/location-type.internal'),
            self::CUSTOMER    => __('warehouses::enums/location-type.customer'),
            self::INVENTORY   => __('warehouses::enums/location-type.inventory'),
            self::PRODUCTION  => __('warehouses::enums/location-type.production'),
            self::TRANSIT     => __('warehouses::enums/location-type.transit'),
        };
    }
}
