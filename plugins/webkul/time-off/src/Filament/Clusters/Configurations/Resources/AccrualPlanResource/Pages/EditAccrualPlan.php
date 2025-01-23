<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource;

class EditAccrualPlan extends EditRecord
{
    protected static string $resource = AccrualPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        $data['company_id'] = $user?->default_company_id;
        $data['creator_id'] = $user->id;

        return $data;
    }
}
