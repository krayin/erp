<?php

namespace Webkul\Employee\Enums;

enum ResumeDisplayType: string
{
    case Classic = 'classic';

    public static function options(): array
    {
        return [
            self::Classic->value => 'Classic',
        ];
    }
}
