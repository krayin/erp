<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Invoice\Models\PaymentTerm;

class CreatePaymentTerm extends CreateRecord
{
    protected static string $resource = PaymentTermResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['sort'] = PaymentTerm::max('sort') + 1;
        $data['creator_id'] = $user->id;
        $data['company_id'] = $user?->default_company_id;

        return $data;
    }
}
