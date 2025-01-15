<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages\EditActivityPlan as BaseEditActivityPlan;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource;

class EditActivityPlan extends BaseEditActivityPlan
{
    protected static string $resource = ActivityPlanResource::class;
}
