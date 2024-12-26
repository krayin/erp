<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource;

class ManageMilestones extends ManageRecords
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('projects::app.filament.clusters.configurations.resources.milestone.pages.manage-milestones.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('projects::app.filament.clusters.configurations.resources.milestone.pages.manage-milestones.header-actions.create.notification.title'))
                        ->body(__('projects::app.filament.clusters.configurations.resources.milestone.pages.manage-milestones.header-actions.create.notification.body')),
                ),
        ];
    }
}
