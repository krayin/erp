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
            self::PERMANENT->value   => 'Permanent',
            self::PRESENT->value     => 'Present',
            self::OTHER->value       => 'Other',
        ];
    }
}
