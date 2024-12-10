<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Filament\Resources\CompanyResource;
use Webkul\Security\Models\Company;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        $data['sequence'] = Company::max('sequence') + 1;

        return $data;
    }
}
