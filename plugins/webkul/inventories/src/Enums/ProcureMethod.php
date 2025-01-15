<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProcureMethod: string implements HasLabel
{
    case MAKE_TO_STOCK = 'make_to_stock';

    case MAKE_TO_ORDER = 'make_to_order';

    case MTS_ELSE_MTO = 'mts_else_mto';

    public function getLabel(): string
    {
        return match ($this) {
            self::MAKE_TO_STOCK => __('inventories::enums/procure-method.make-to-stock'),
            self::MAKE_TO_ORDER => __('inventories::enums/procure-method.make-to-order'),
            self::MTS_ELSE_MTO  => __('inventories::enums/procure-method.mts-else-mto'),
        };
    }
}
