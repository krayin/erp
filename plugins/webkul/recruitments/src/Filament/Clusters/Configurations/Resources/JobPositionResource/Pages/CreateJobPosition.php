<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages\CreateJobPosition as BaseCreateJobPosition;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource;

class CreateJobPosition extends BaseCreateJobPosition
{
    protected static string $resource = JobPositionResource::class;
}
