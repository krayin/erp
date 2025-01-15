<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;

class ManageRoutes extends ManageRelatedRecords
{
    protected static string $resource = WarehouseResource::class;

    protected static string $relationship = 'routes';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.title');
    }

    public function form(Form $form): Form
    {
        return RouteResource::form($form);
    }

    public function table(Table $table): Table
    {
        return RouteResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->fillForm(function (array $arguments): array {
                        return [
                            'warehouse_selectable' => true,
                            'warehouses'           => [$this->getOwnerRecord()->id],
                        ];
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['warehouse_selectable'] = true;

                        $data['creator_id'] = Auth::id();

                        $data['company_id'] = $data['company_id'] ?? Auth::user()->default_company_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
