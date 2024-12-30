<?php

namespace Webkul\Employee\Enums;

enum Gender: string
{
    case Male = 'male';

    case Female = 'female';

    case Other = 'other';

    public static function options(): array
    {
        return [
            self::Male->value   => __('employees::enums/gender.male'),
            self::Female->value => __('employees::enums/gender.female'),
            self::Other->value  => __('employees::enums/gender.other'),
        ];
    }
}
