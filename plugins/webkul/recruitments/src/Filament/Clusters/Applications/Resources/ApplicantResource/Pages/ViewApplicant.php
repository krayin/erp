<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewApplicant extends ViewRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
