<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum MoveState: string implements HasLabel
{
    case DRAFT = 'draft';

    case ASSIGNED = 'assigned';

    case DONE = 'done';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT    => __('inventories::enums/move-state.draft'),
            self::ASSIGNED => __('inventories::enums/move-state.assigned'),
            self::DONE     => __('inventories::enums/move-state.done'),
        };
    }
}
