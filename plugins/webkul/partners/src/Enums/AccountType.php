<?php

namespace Webkul\Partner\Enums;

enum AccountType: string
{
    case INDIVIDUAL = 'individual';

    case COMPANY = 'company';

    case EMPLOYEE = 'employee';

    public static function options(): array
    {
        return [
            self::INDIVIDUAL->value => __('partners::enums/account-type.individual'),
            self::COMPANY->value    => __('partners::enums/account-type.company'),
            self::EMPLOYEE->value   => __('partners::enums/account-type.employee'),
        ];
    }
}
