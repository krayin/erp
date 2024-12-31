<?php

namespace Webkul\Support\Enums;

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
            self::ON_DEMAND->value => __('support::enums/activity-responsible-type.on-demand'),
            self::OTHER->value     => __('support::enums/activity-responsible-type.other'),
            self::COACH->value     => __('support::enums/activity-responsible-type.coach'),
            self::MANAGER->value   => __('support::enums/activity-responsible-type.manager'),
            self::EMPLOYEE->value  => __('support::enums/activity-responsible-type.employee'),
        ];
    }
}
