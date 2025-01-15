<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum OperationType: string implements HasLabel
{
    case INCOMING = 'incoming';

    case OUTGOING = 'outgoing';

    case INTERNAL = 'internal';

    case DROPSHIP = 'dropship';

    public function getLabel(): string
    {
        return match ($this) {
            self::INCOMING => __('inventories::enums/operation-type.incoming'),
            self::OUTGOING => __('inventories::enums/operation-type.outgoing'),
            self::INTERNAL => __('inventories::enums/operation-type.internal'),
            self::DROPSHIP => __('inventories::enums/operation-type.dropship'),
        };
    }
}
