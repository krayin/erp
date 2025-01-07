<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStages extends ViewRecord
{
    protected static string $resource = StageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
