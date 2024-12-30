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
            self::PRIVATE->value  => __('projects::enums/project-visibility.private'),
            self::INTERNAL->value => __('projects::enums/project-visibility.internal'),
            self::PUBLIC->value   => __('projects::enums/project-visibility.public'),
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
