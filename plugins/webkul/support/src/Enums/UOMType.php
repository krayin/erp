<?php

namespace Webkul\Support\Enums;

use Filament\Support\Contracts\HasLabel;

enum UOMType: string implements HasLabel
{
    case REFERENCE = 'reference';

    case BIGGER = 'bigger';

    case SMALLER = 'smaller';

    public function getLabel(): string
    {
        return match ($this) {
            self::REFERENCE => __('support::enums/uom-type.reference'),
            self::BIGGER    => __('support::enums/uom-type.bigger'),
            self::SMALLER   => __('support::enums/uom-type.smaller'),
        };
    }
}
