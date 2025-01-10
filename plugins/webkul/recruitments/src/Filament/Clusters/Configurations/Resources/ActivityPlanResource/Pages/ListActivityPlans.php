<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages\ListActivityPlans as BaseListActivityPlans;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ListActivityPlans extends BaseListActivityPlans
{
    protected static string $resource = ActivityPlanResource::class;

    protected static ?string $pluginName = 'recruitments';
}
