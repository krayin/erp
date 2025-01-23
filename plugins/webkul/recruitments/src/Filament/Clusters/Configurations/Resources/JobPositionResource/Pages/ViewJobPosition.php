<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages\ViewJobPosition as BaseViewJobPosition;

class ViewJobPosition extends BaseViewJobPosition
{
    protected static string $resource = JobPositionResource::class;
}
