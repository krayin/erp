<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ApplicantCategoryResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ApplicantCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplicantCategories extends ListRecords
{
    protected static string $resource = ApplicantCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
