<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobByPosition extends EditRecord
{
    protected static string $resource = JobByPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
