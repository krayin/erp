<?php

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\BankAccountResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\BankAccountResource;
use Webkul\Partner\Models\BankAccount;

class ManageBankAccounts extends ManageRecords
{
    protected static string $resource = BankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('contacts::filament/clusters/configurations/resources/bank-account/pages/manage-bank-accounts.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('contacts::filament/clusters/configurations/resources/bank-account/pages/manage-bank-accounts.header-actions.create.notification.title'))
                        ->body(__('contacts::filament/clusters/configurations/resources/bank-account/pages/manage-bank-accounts.header-actions.create.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('contacts::filament/clusters/configurations/resources/bank-account/pages/manage-bank-accounts.tabs.all'))
                ->badge(BankAccount::count()),
            'archived' => Tab::make(__('contacts::filament/clusters/configurations/resources/bank-account/pages/manage-bank-accounts.tabs.archived'))
                ->badge(BankAccount::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
