<?php

namespace Webkul\Inventory\Enums;

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
            self::SUPPLIER    => __('inventories::enums/location-type.supplier'),
            self::VIEW        => __('inventories::enums/location-type.view'),
            self::INTERNAL    => __('inventories::enums/location-type.internal'),
            self::CUSTOMER    => __('inventories::enums/location-type.customer'),
            self::INVENTORY   => __('inventories::enums/location-type.inventory'),
            self::PRODUCTION  => __('inventories::enums/location-type.production'),
            self::TRANSIT     => __('inventories::enums/location-type.transit'),
        };
    }
}
