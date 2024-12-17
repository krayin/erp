<?php

namespace Webkul\Employee\Enums;

enum ActivityResponsibleType: string
{
    case ON_DEMAND = 'on_demand';

    case OTHER = 'other';

    case COACH = 'coach';

    case MANAGER = 'manager';

    case EMPLOYEE = 'employee';

    /**
     * Returns an array of options for dropdowns or selects.
     */
    public static function options(): array
    {
        return [
            self::ON_DEMAND->value => 'Ask at launch',
            self::OTHER->value     => 'Default user',
            self::COACH->value     => 'Coach',
            self::MANAGER->value   => 'Manager',
            self::EMPLOYEE->value  => 'Employee',
        ];
    }
}
