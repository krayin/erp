<?php

namespace Webkul\Invoice\Enums;

use Filament\Support\Contracts\HasLabel;

enum RepartitionType: string implements HasLabel
{
    case BASE = 'base';

    case TAX = 'tax';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BASE => __('invoices::enums/repartition-type.base'),
            self::TAX   => __('invoices::enums/repartition-type.tax'),
        };
    }

    public static function options(): array
    {
        return [
            self::BASE->value => __('invoices::enums/repartition-type.base'),
            self::TAX->value => __('invoices::enums/repartition-type.tax'),
        ];
    }
}
