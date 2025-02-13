<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\OrderTemplateProductResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\OrderTemplateProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrderTemplateProduct extends CreateRecord
{
    protected static string $resource = OrderTemplateProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user->default_company_id;

        $data['creator_id'] = $user->id;

        return $data;
    }
}
