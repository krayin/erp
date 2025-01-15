<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum GroupPropagation: string implements HasLabel
{
    case NONE = 'none';

    case PROPAGATE = 'propagate';

    case FIXED = 'fixed';

    public function getLabel(): string
    {
        return match ($this) {
            self::NONE      => __('inventories::enums/group-propagation.none'),
            self::PROPAGATE => __('inventories::enums/group-propagation.propagate'),
            self::FIXED     => __('inventories::enums/group-propagation.fixed'),
        };
    }
}
