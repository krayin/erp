<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource;
use Webkul\Support\Models\ActivityPlan;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    $user = Auth::user();

                    $data['plugin'] = 'employees';

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->defaultCompany?->id;

                    return $data;
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(ActivityPlan::where('plugin', 'employees')->count()),
            'archived' => Tab::make('Archived')
                ->badge(ActivityPlan::where('plugin', 'employees')->onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
