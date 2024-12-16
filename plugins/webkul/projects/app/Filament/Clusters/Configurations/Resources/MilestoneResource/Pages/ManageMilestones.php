<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\Pages;

use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageMilestones extends ManageRecords
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();
    
        return $data;
    }
}
