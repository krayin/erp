<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PickingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Models\PickingType;

class ListPickingTypes extends ListRecords
{
    protected static string $resource = PickingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('inventories::filament/clusters/configurations/resources/picking-type/pages/list-picking-types.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->default_company_id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/picking-type/pages/list-picking-types.header-actions.create.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/picking-type/pages/list-picking-types.header-actions.create.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('inventories::filament/clusters/configurations/resources/picking-type/pages/list-picking-types.tabs.all'))
                ->badge(PickingType::count()),
            'archived' => Tab::make(__('inventories::filament/clusters/configurations/resources/picking-type/pages/list-picking-types.tabs.archived'))
                ->badge(PickingType::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
