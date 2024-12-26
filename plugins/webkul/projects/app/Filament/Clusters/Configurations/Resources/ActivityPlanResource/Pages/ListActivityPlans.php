<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource;
use Webkul\Support\Models\ActivityPlan;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Activity Plan')
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    $user = Auth::user();

                    $data['plugin'] = 'projects';

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->defaultCompany?->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.list.header-actions.delete.notification.title'))
                        ->body(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.list.header-actions.delete.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.list.tabs.all'))
                ->badge(ActivityPlan::where('plugin', 'projects')->count()),
            'archived' => Tab::make(__('projects::app.filament.clusters.configurations.resources.activity-plan.pages.list.tabs.archived'))
                ->badge(ActivityPlan::where('plugin', 'projects')->onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
