<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum MoveType: string implements HasLabel
{
    case DIRECT = 'direct';

    case ONE = 'one';

    public function getLabel(): string
    {
        return match ($this) {
            self::DIRECT => __('inventories::enums/move-type.direct'),
            self::ONE    => __('inventories::enums/move-type.one'),
        };
    }
}
