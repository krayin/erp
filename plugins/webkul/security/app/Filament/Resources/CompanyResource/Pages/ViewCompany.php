<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Security\Filament\Resources\CompanyResource;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
