<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum PickingType: string implements HasLabel
{
    case INCOMING = 'incoming';

    case OUTGOING = 'outgoing';

    case INTERNAL = 'internal';

    case DROPSHIP = 'dropship';

    public function getLabel(): string
    {
        return match ($this) {
            self::INCOMING => __('inventories::enums/picking-type.incoming'),
            self::OUTGOING => __('inventories::enums/picking-type.outgoing'),
            self::INTERNAL => __('inventories::enums/picking-type.internal'),
            self::DROPSHIP => __('inventories::enums/picking-type.dropship'),
        };
    }
}
