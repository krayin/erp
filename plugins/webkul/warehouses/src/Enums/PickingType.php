<?php

namespace Webkul\Warehouse\Enums;

enum PickingType: string
{
    case INCOMING = 'incoming';

    case OUTGOING = 'outgoing';

    case INTERNAL = 'internal';

    case DROPSHIP = 'dropship';

    public static function options(): array
    {
        return [
            self::INCOMING->value => __('warehouses::enums/picking-type.incoming'),
            self::OUTGOING->value => __('warehouses::enums/picking-type.outgoing'),
            self::INTERNAL->value => __('warehouses::enums/picking-type.internal'),
            self::DROPSHIP->value => __('warehouses::enums/picking-type.dropship'),
        ];
    }
}
