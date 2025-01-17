<?php

namespace Webkul\Inventory\Settings;

use Spatie\LaravelSettings\Settings;

class TraceabilitySettings extends Settings
{
    public bool $enable_lost_serial_numbers;

    public bool $enable_expiration_dates;

    public bool $display_on_delivery_slips;

    public bool $enable_consignments;

    public static function group(): string
    {
        return 'inventories.traceability';
    }
}
