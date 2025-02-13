<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum MoveState: string implements HasLabel
{
    case DRAFT = 'draft';

    case POSTED = 'posted';

    case CANCEL = 'cancel';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => __('invoices::enums/move-state.draft'),
            self::POSTED => __('invoices::enums/move-state.posted'),
            self::CANCEL => __('invoices::enums/move-state.cancel'),
        };
    }

    public static function options(): array
    {
        return [
            self::DRAFT->value => __('invoices::enums/move-state.draft'),
            self::POSTED->value => __('invoices::enums/move-state.posted'),
            self::CANCEL->value => __('invoices::enums/move-state.cancel'),
        ];
    }
}
