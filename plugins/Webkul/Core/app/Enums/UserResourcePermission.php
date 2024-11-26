<?php

namespace Webkul\Core\Enums;

enum UserResourcePermission: string
{
    case GROUP = 'group';

    case INDIVIDUAL = 'individual';

    case GLOBAL = 'global';

    public static function options(): array
    {
        return [
            self::GROUP->value      => 'Group',
            self::INDIVIDUAL->value => 'Individual',
            self::GLOBAL->value     => 'Global',
        ];
    }
}
