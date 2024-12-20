<?php

namespace Webkul\Project\Enums;

enum ProjectVisibility: string
{
    case PRIVATE = 'private';
    case INTERNAL = 'internal';
    case PUBLIC = 'public';

    public static function options(): array
    {
        return [
            self::PRIVATE->value  => 'Private',
            self::INTERNAL->value => 'Internal',
            self::PUBLIC->value   => 'Public',
        ];
    }
}
