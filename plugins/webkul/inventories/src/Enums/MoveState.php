<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum MoveState: string implements HasLabel
{
    case DRAFT = 'draft';

    case CONFIRMED = 'confirmed';

    case ASSIGNED = 'assigned';

    case DONE = 'done';

    case CANCELED = 'canceled';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT    => __('inventories::enums/move-state.draft'),
            self::CONFIRMED => __('inventories::enums/move-state.confirmed'),
            self::ASSIGNED => __('inventories::enums/move-state.assigned'),
            self::DONE     => __('inventories::enums/move-state.done'),
            self::CANCELED     => __('inventories::enums/move-state.canceled'),
        };
    }
}
