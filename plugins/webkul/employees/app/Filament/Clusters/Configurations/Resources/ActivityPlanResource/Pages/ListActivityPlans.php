<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource;
use Webkul\Support\Models\ActivityPlan;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    $user = Auth::user();

                    $data['plugin'] = 'employees';

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->defaultCompany?->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.tabs.all'))
                ->badge(ActivityPlan::where('plugin', 'employees')->count()),
            'archived' => Tab::make(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.tabs.archived'))
                ->badge(ActivityPlan::where('plugin', 'employees')->onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
