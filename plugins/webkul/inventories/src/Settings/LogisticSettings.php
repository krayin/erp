<?php

namespace Webkul\Inventory\Settings;

use Spatie\LaravelSettings\Settings;

class LogisticSettings extends Settings
{
    public bool $enable_dropshipping;

    public static function group(): string
    {
        return 'inventories_logistic';
    }
}
