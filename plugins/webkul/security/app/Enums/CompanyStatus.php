<?php

namespace Webkul\Security\Enums;

enum CompanyStatus: string
{
    case ACTIVE = 'active';

    case INACTIVE = 'inactive';

    public static function options(): array
    {
        return [
            self::ACTIVE->value      => 'Active',
            self::INACTIVE->value => 'Inactive',
        ];
    }
}
