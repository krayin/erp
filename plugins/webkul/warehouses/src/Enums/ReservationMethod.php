<?php

namespace Webkul\Warehouse\Enums;

enum ReservationMethod: string
{
    case AT_CONFIRM = 'at_confirm';

    case MANUAL = 'manual';

    case BY_DATE = 'by_date';

    public static function options(): array
    {
        return [
            self::AT_CONFIRM->value => __('warehouses::enums/picking-type.at-confirm'),
            self::MANUAL->value     => __('warehouses::enums/picking-type.manual'),
            self::BY_DATE->value    => __('warehouses::enums/picking-type.by-date'),
        ];
    }
}
