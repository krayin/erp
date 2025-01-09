<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum CreateBackorder: string implements HasLabel
{
    case ASK = 'ask';

    case ALWAYS = 'always';

    case NEVER = 'never';

    public function getLabel(): string
    {
        return match ($this) {
            self::ASK    => __('inventories::enums/create-backorder.ask'),
            self::ALWAYS => __('inventories::enums/create-backorder.always'),
            self::NEVER  => __('inventories::enums/create-backorder.never'),
        };
    }
}
