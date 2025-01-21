<?php

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum CarryoverMonth: string implements HasLabel
{
    case JAN = 'jan';
    case FEB = 'feb';
    case MAR = 'mar';
    case APR = 'apr';
    case MAY = 'may';
    case JUN = 'jun';
    case JUL = 'jul';
    case AUG = 'aug';
    case SEP = 'sep';
    case OCT = 'oct';
    case NOV = 'nov';
    case DEC = 'dec';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::JAN => __('January'),
            self::FEB => __('February'),
            self::MAR => __('March'),
            self::APR => __('April'),
            self::MAY => __('May'),
            self::JUN => __('June'),
            self::JUL => __('July'),
            self::AUG => __('August'),
            self::SEP => __('September'),
            self::OCT => __('October'),
            self::NOV => __('November'),
            self::DEC => __('December'),
        };
    }

    public static function options(): array
    {
        return [
            self::JAN->value => __('January'),
            self::FEB->value => __('February'),
            self::MAR->value => __('March'),
            self::APR->value => __('April'),
            self::MAY->value => __('May'),
            self::JUN->value => __('June'),
            self::JUL->value => __('July'),
            self::AUG->value => __('August'),
            self::SEP->value => __('September'),
            self::OCT->value => __('October'),
            self::NOV->value => __('November'),
            self::DEC->value => __('December'),
        ];
    }
}
