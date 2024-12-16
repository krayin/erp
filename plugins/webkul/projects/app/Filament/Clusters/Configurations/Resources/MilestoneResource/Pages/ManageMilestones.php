<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\Pages;

use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMilestones extends ManageRecords
{
    protected static string $resource = MilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
