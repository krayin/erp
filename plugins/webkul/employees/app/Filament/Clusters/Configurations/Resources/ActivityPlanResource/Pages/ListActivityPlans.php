<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    $data['user_id'] = Auth::user()->id;

                    return $data;
                }),
        ];
    }
}
