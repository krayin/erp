<?php

namespace Webkul\Warehouse\Enums;

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
            self::INCOMING => __('warehouses::enums/picking-type.incoming'),
            self::OUTGOING => __('warehouses::enums/picking-type.outgoing'),
            self::INTERNAL => __('warehouses::enums/picking-type.internal'),
            self::DROPSHIP => __('warehouses::enums/picking-type.dropship'),
        };
    }
}
