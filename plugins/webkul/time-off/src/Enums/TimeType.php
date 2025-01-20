<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum TimeType: string implements HasLabel
{
    case LEAVE = 'leave';

    case OTHER = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LEAVE => __('Absence'),
            self::OTHER => __('Worked Time'),
        };
    }

    public static function options(): array
    {
        return [
            self::LEAVE->value => __('Absence'),
            self::OTHER->value => __('Worked Time'),
        ];
    }
}
