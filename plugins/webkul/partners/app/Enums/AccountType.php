<?php

namespace Webkul\Partner\Enums;

enum AccountType: string
{
    case INDIVIDUAL = 'individual';

    case COMPANY = 'company';

    public static function options(): array
    {
        return [
            self::INDIVIDUAL->value => 'Individual',
            self::COMPANY->value    => 'Company',
        ];
    }
}
