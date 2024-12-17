<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\TaskStageResource\Pages;

use Filament\Actions;
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
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                }),
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
