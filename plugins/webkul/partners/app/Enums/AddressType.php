<?php

namespace Webkul\Partner\Enums;

enum AddressType: string
{
    case PERMANENT = 'permanent';

    case PRESENT = 'present';

    case OTHER = 'other';

    public static function options(): array
    {
        return [
            self::PERMANENT->value => __('partners::enums/address-type.permanent'),
            self::PRESENT->value   => __('partners::enums/address-type.present'),
            self::OTHER->value     => __('partners::enums/address-type.other'),
        ];
    }
}
