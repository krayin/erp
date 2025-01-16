<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Filament\Actions;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages\ListJobPositions as BaseListJobPositions;

class ListJobPositions extends BaseListJobPositions
{
    protected static string $resource = JobPositionResource::class;
}
