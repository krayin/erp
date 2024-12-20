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

    public static function icons(): array
    {
        return [
            self::PRIVATE->value  => 'heroicon-o-lock-closed',
            self::INTERNAL->value => 'heroicon-o-building-office',
            self::PUBLIC->value   => 'heroicon-o-globe-alt',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PRIVATE->value  => 'danger',
            self::INTERNAL->value => 'warning',
            self::PUBLIC->value   => 'success',
        ];
    }
}