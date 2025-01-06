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
            self::AT_CONFIRM->value => __('warehouses::enums/reservation-method.at-confirm'),
            self::MANUAL->value     => __('warehouses::enums/reservation-method.manual'),
            self::BY_DATE->value    => __('warehouses::enums/reservation-method.by-date'),
        ];
    }
}
