<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Enums\OrderState;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['creator_id'] = $user->id;

        $data['user_id'] = $user->id;

        $data['company_id'] = $user->default_company_id;

        $data['name'] = 'Quotation-' . time();

        $data['state'] = OrderState::DRAFT->value;

        $data['invoice_status'] = 'no';

        return $data;
    }
}
