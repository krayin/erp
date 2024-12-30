<?php

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\BankResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\BankResource;
use Webkul\Support\Models\Bank;

class ManageBanks extends ManageRecords
{
    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('contacts::filament/clusters/configurations/resources/bank/pages/manage-banks.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('contacts::filament/clusters/configurations/resources/bank/pages/manage-banks.header-actions.create.notification.title'))
                        ->body(__('contacts::filament/clusters/configurations/resources/bank/pages/manage-banks.header-actions.create.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('contacts::filament/clusters/configurations/resources/bank/pages/manage-banks.tabs.all'))
                ->badge(Bank::count()),
            'archived' => Tab::make(__('contacts::filament/clusters/configurations/resources/bank/pages/manage-banks.tabs.archived'))
                ->badge(Bank::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
