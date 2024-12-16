<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource\Pages;

use Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Components\Tab;
use Webkul\Project\Models\ProjectStage;
use Illuminate\Support\Facades\Auth;

class ManageProjectStages extends ManageRecords
{
    protected static string $resource = ProjectStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();
    
        return $data;
    }
}
