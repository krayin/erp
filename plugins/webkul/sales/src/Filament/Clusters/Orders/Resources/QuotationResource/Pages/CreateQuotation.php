<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Enums\OrderState;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user->default_company_id;

        $data['name'] = 'Quotation-' . time();

        $data['state'] = OrderState::DRAFT->value;

        $data['invoice_status'] = 'no';

        return $data;
    }
}
