<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages\ListJobPositions as JobPositionResource;

class ListJobByPositions extends JobPositionResource
{
    protected static string $resource = JobByPositionResource::class;

    public function getHeaderActions(): array
    {
        return [];
    }
}
