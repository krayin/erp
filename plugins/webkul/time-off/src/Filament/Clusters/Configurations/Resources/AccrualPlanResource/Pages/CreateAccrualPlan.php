<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource;

class CreateAccrualPlan extends CreateRecord
{
    protected static string $resource = AccrualPlanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user?->default_company_id;
        $data['creator_id'] = $user->id;

        return $data;
    }
}
