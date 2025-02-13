<?php

namespace Webkul\Sale\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderDisplayType: string implements HasLabel
{
    case SECTION = 'section';

    case NOTE = 'note';

    public function getLabel(): string
    {
        return match ($this) {
            self::SECTION => __('sales::enums/order-display-type.section'),
            self::NOTE    => __('sales::enums/order-display-type.note'),
        };
    }

    public function options(): array
    {
        return [
            self::SECTION->value => __('sales::enums/order-display-type.section'),
            self::NOTE->value    => __('sales::enums/order-display-type.note'),
        ];
    }
}
