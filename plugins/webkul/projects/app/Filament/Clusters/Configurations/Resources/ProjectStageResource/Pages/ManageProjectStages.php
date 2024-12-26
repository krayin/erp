<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource;
use Webkul\Project\Models\ProjectStage;
use Filament\Notifications\Notification;

class ManageProjectStages extends ManageRecords
{
    protected static string $resource = ProjectStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Project Stage')
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Project stage created')
                        ->body('The project stage has been created successfully.'),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Stages')
                ->badge(ProjectStage::count()),
            'archived' => Tab::make('Archived')
                ->badge(ProjectStage::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
