<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Security\Filament\Resources\CompanyResource;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
