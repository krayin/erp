<?php

namespace Webkul\Employee\Enums;

enum MaritalStatus: string
{
    case Single = 'single';

    case Married = 'married';

    case Divorced = 'divorced';

    case Widowed = 'widowed';

    public static function options(): array
    {
        return [
            self::Single->value   => __('employees::enums/marital-status.single'),
            self::Married->value  => __('employees::enums/marital-status.married'),
            self::Divorced->value => __('employees::enums/marital-status.divorced'),
            self::Widowed->value  => __('employees::enums/marital-status.widowed'),
        ];
    }
}
