<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages\ViewActivityPlan as BaseViewActivityPlan;

class ViewActivityPlan extends BaseViewActivityPlan
{
    protected static string $resource = ActivityPlanResource::class;
}
