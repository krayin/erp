<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\TaskStageResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TaskStageResource;
use Webkul\Project\Models\TaskStage;

class ManageTaskStages extends ManageRecords
{
    protected static string $resource = TaskStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Task Stage')
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Task stage created')
                        ->body('The task stage has been created successfully.'),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Stages')
                ->badge(TaskStage::count()),
            'archived' => Tab::make('Archived')
                ->badge(TaskStage::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
