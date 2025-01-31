<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommunicationStandard: string implements HasLabel
{
    case AUREUS = 'aureus';

    case EUROPEAN = 'european';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AUREUS   => __('invoices::enums/communication-standard.aureus'),
            self::EUROPEAN => __('invoices::enums/communication-standard.european'),
        };
    }

    public static function options(): array
    {
        return [
            self::AUREUS->value   => __('invoices::enums/communication-standard.aureus'),
            self::EUROPEAN->value => __('invoices::enums/communication-standard.european'),
        ];
    }
}
