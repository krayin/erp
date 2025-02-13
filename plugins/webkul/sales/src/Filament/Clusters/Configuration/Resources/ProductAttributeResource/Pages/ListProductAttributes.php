<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Webkul\Product\Models\Attribute;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource;

class ListProductAttributes extends ListRecords
{
    protected static string $resource = ProductAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('sales::filament/clusters/configurations/resources/product-attribute/pages/list-product-attributes.header-actions.create.label'))
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
                        ->title(__('sales::filament/clusters/configurations/resources/product-attribute/pages/list-product-attributes.header-actions.create.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/product-attribute/pages/list-product-attributes.header-actions.create.notification.body')),
                ),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('sales::filament/clusters/configurations/resources/product-attribute/pages/list-product-attributes.tabs.all'))
                ->badge(Attribute::count()),
            'archived' => Tab::make(__('sales::filament/clusters/configurations/resources/product-attribute/pages/list-product-attributes.tabs.archived'))
                ->badge(Attribute::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
