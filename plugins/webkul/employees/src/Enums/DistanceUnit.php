<?php

namespace Webkul\Employee\Enums;

enum DistanceUnit: string
{
    case KILOMETER = 'kilometer';

    case METER = 'meter';

    public static function options(): array
    {
        return [
            self::KILOMETER->value => __('employees::enums/distance-unit.kilometer'),
            self::METER->value     => __('employees::enums/distance-unit.meter'),
        ];
    }
}
