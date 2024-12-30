<?php

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\IndustryResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\IndustryResource;
use Webkul\Partner\Models\Tag;

class ManageIndustries extends ManageRecords
{
    protected static string $resource = IndustryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('contacts::filament/clusters/configurations/resources/industry/pages/manage-industries.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id'] = Auth::id();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('contacts::filament/clusters/configurations/resources/industry/pages/manage-industries.header-actions.create.notification.title'))
                        ->body(__('contacts::filament/clusters/configurations/resources/industry/pages/manage-industries.header-actions.create.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('contacts::filament/clusters/configurations/resources/industry/pages/manage-industries.tabs.all'))
                ->badge(Tag::count()),
            'archived' => Tab::make(__('contacts::filament/clusters/configurations/resources/industry/pages/manage-industries.tabs.archived'))
                ->badge(Tag::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
