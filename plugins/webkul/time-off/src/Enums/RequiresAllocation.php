<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum RequiresAllocation: string implements HasLabel
{
    case YES = 'yes';

    case NO = 'no';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::YES => __('Yes'),
            self::NO  => __('No Limit'),
        };
    }

    public static function options(): array
    {
        return [
            self::YES->value            => __('Yes'),
            self::NO->value             => __('No Limit'),
        ];
    }
}
