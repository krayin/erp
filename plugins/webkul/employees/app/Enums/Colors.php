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
            self::Danger->value  => 'Danger',
            self::Gray->value    => 'Gray',
            self::Info->value    => 'Info',
            self::Success->value => 'Success',
            self::Warning->value => 'Warning',
        ];
    }
}
