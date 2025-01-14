<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJobByPosition extends CreateRecord
{
    protected static string $resource = JobByPositionResource::class;
}
