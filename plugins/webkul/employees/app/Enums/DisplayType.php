<?php

namespace Webkul\Employee\Enums;

enum DisplayType: string
{
    case Working = 'working';

    case Off = 'off';

    case Holiday = 'holiday';

    public static function options(): array
    {
        return [
            self::Working->value       => 'Working',
            self::Off->value           => 'Off',
            self::Holiday->value       => 'Holiday',
        ];
    }
}
