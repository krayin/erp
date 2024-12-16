<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Filament\Resources\CompanyResource;
use Webkul\Support\Models\Company;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            'user_id'  => Auth::user()->id,
            'sequence' => Company::max('sequence') + 1,
            ...$data,
        ];
    }
}
