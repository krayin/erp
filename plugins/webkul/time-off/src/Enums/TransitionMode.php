<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum TransitionMode: string implements HasLabel
{
    case IMMEDIATELY = 'immediately';

    case END_OF_ACCRUAL = 'end_of_accrual';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::IMMEDIATELY => __('Immediately'),
            self::END_OF_ACCRUAL => __('After this accrual period'),
        };
    }

    public static function options(): array
    {
        return [
            self::IMMEDIATELY->value => __('Immediately'),
            self::END_OF_ACCRUAL->value => __('After this accrual period'),
        ];
    }
}
