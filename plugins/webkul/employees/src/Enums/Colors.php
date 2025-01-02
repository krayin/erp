<?php

namespace Webkul\Employee\Enums;

enum Colors: string
{
    case Danger = 'danger';

    case Gray = 'gray';

    case Info = 'info';

    case Success = 'success';

    case Warning = 'warning';

    public static function options(): array
    {
        return [
            self::Danger->value  => __('employees::enums/colors.danger'),
            self::Gray->value    => __('employees::enums/colors.gray'),
            self::Info->value    => __('employees::enums/colors.info'),
            self::Success->value => __('employees::enums/colors.success'),
            self::Warning->value => __('employees::enums/colors.warning'),
        ];
    }
}
