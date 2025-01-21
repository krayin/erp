<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum AccruedGainTime: string implements HasLabel
{
    case START = 'start';

    case END = 'end';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::START => __('At the start of the accrual period'),
            self::END   => __('At the end of the accrual period'),
        };
    }

    public static function options(): array
    {
        return [
            self::START->value => __('At the start of the accrual period'),
            self::END->value   => __('At the end of the accrual period'),
        ];
    }
}
