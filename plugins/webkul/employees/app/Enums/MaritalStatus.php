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
            self::Single->value   => 'Single',
            self::Married->value  => 'Married',
            self::Divorced->value => 'Divorced',
            self::Widowed->value  => 'Widowed',
        ];
    }
}
