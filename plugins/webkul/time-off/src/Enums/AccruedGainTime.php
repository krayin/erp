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
            self::START => __('time_off::enums/accrued-gain-time.start'),
            self::END   => __('time_off::enums/accrued-gain-time.end'),
        };
    }

    public static function options(): array
    {
        return [
            self::START->value => __('time_off::enums/accrued-gain-time.start'),
            self::END->value   => __('time_off::enums/accrued-gain-time.end'),
        ];
    }
}
