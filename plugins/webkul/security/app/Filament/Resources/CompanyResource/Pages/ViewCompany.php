<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Webkul\Security\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\EditAction::make(),
        ];
    }
}
