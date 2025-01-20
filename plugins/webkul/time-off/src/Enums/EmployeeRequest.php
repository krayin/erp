<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum EmployeeRequest: string implements HasLabel
{
    case YES = 'yes';

    case NO = 'no';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::YES => __('Extra Days Request Allowed'),
            self::NO  => __('Not Allowed'),
        };
    }

    public static function options(): array
    {
        return [
            self::YES->value => __('Extra Days Request Allowed'),
            self::NO->value  => __('Not Allowed'),
        ];
    }
}
