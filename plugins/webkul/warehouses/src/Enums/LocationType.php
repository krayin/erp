<?php

namespace Webkul\Warehouse\Enums;

enum LocationType: string
{
    case SUPPLIER = 'supplier';

    case VIEW = 'view';

    case INTERNAL = 'internal';

    case CUSTOMER = 'customer';

    case INVENTORY = 'inventory';

    case PRODUCTION = 'production';

    case TRANSIT = 'transit';

    public static function options(): array
    {
        return [
            self::SUPPLIER->value    => __('warehouses::enums/location-type.supplier'),
            self::VIEW->value        => __('warehouses::enums/location-type.view'),
            self::INTERNAL->value    => __('warehouses::enums/location-type.internal'),
            self::CUSTOMER->value    => __('warehouses::enums/location-type.customer'),
            self::INVENTORY->value   => __('warehouses::enums/location-type.inventory'),
            self::PRODUCTION->value  => __('warehouses::enums/location-type.production'),
            self::TRANSIT->value     => __('warehouses::enums/location-type.transit'),
        ];
    }
}
