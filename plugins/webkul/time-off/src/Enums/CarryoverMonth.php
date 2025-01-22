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
            self::JAN => __('time_off::enums/carry-over-month.jan'),
            self::FEB => __('time_off::enums/carry-over-month.feb'),
            self::MAR => __('time_off::enums/carry-over-month.mar'),
            self::APR => __('time_off::enums/carry-over-month.apr'),
            self::MAY => __('time_off::enums/carry-over-month.may'),
            self::JUN => __('time_off::enums/carry-over-month.jun'),
            self::JUL => __('time_off::enums/carry-over-month.jul'),
            self::AUG => __('time_off::enums/carry-over-month.aug'),
            self::SEP => __('time_off::enums/carry-over-month.sep'),
            self::OCT => __('time_off::enums/carry-over-month.oct'),
            self::NOV => __('time_off::enums/carry-over-month.nov'),
            self::DEC => __('time_off::enums/carry-over-month.dec'),
        };
    }

    public static function options(): array
    {
        return [
            self::JAN->value => __('time_off::enums/carry-over-month.jan'),
            self::FEB->value => __('time_off::enums/carry-over-month.feb'),
            self::MAR->value => __('time_off::enums/carry-over-month.mar'),
            self::APR->value => __('time_off::enums/carry-over-month.apr'),
            self::MAY->value => __('time_off::enums/carry-over-month.may'),
            self::JUN->value => __('time_off::enums/carry-over-month.jun'),
            self::JUL->value => __('time_off::enums/carry-over-month.jul'),
            self::AUG->value => __('time_off::enums/carry-over-month.aug'),
            self::SEP->value => __('time_off::enums/carry-over-month.sep'),
            self::OCT->value => __('time_off::enums/carry-over-month.oct'),
            self::NOV->value => __('time_off::enums/carry-over-month.nov'),
            self::DEC->value => __('time_off::enums/carry-over-month.dec'),
        ];
    }
}
