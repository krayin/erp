<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages\ListJobPositions as JobPositionResource;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource;

class ListJobByPositions extends JobPositionResource
{
    protected static string $resource = JobByPositionResource::class;

    public function getHeaderActions(): array
    {
        return [];
    }
}
