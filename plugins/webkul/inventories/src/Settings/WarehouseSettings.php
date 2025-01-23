<?php

namespace Webkul\Inventory\Settings;

use Spatie\LaravelSettings\Settings;

class WarehouseSettings extends Settings
{
    public bool $enable_locations;

    public bool $enable_multi_steps_routes;

    public static function group(): string
    {
        return 'inventories_warehouse';
    }
}
