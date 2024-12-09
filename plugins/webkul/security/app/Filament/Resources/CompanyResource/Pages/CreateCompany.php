<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Filament\Resources\CompanyResource;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        return $data;
    }
}
