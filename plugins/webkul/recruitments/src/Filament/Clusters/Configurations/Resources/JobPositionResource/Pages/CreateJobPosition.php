<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages\CreateJobPosition as BaseCreateJobPosition;

class CreateJobPosition extends BaseCreateJobPosition
{
    protected static string $resource = JobPositionResource::class;
}
