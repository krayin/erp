<?php

namespace Webkul\Partner\Enums;

enum AddressType: string
{
    case PERMANENT = 'permanent';

    case PRESENT = 'present';

    case INVOICE = 'invoice';

    case DELIVERY = 'delivery';

    case OTHER = 'other';

    public static function options(): array
    {
        return [
            self::PERMANENT->value  => __('partners::enums/address-type.permanent'),
            self::PRESENT->value    => __('partners::enums/address-type.present'),
            self::INVOICE->value    => __('partners::enums/address-type.invoice'),
            self::DELIVERY->value   => __('partners::enums/address-type.delivery'),
            self::OTHER->value      => __('partners::enums/address-type.other'),
        ];
    }
}
