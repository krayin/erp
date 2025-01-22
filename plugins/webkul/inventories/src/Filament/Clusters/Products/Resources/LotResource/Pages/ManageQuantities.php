<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Operation;
use Webkul\Inventory\Models\ProductQuantity;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Inventory\Models\Warehouse;

class ManageQuantities extends ManageRelatedRecords
{
    protected static string $resource = LotResource::class;

    protected static string $relationship = 'quantities';

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function canAccess(array $parameters = []): bool
    {
        $canAccess = parent::canAccess($parameters);

        if (! $canAccess) {
            return false;
        }

        return app(OperationSettings::class)->enable_packages
            || app(WarehouseSettings::class)->enable_locations
            || (
                app(TraceabilitySettings::class)->enable_lots_serial_numbers
                && $parameters['record']->tracking != Enums\ProductTracking::QTY
            );
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.columns.product'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.columns.location'))
                    ->visible(fn (WarehouseSettings $warehouseSettings) => $warehouseSettings->enable_locations),
                Tables\Columns\TextColumn::make('storageCategory.name')
                    ->label(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.columns.storage-category'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('package.name')
                    ->label(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.columns.package'))
                    ->placeholder('—')
                    ->visible(fn (OperationSettings $operationSettings) => $operationSettings->enable_packages),
                Tables\Columns\TextInputColumn::make('quantity')
                    ->label(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.columns.on-hand'))
                    ->searchable()
                    ->sortable()
                    ->beforeStateUpdated(function ($record, $state) {
                        $previousQuantity = $record->quantity;

                        if ($previousQuantity == $state) {
                            return;
                        }

                        $adjustmentLocation = Location::where('type', Enums\LocationType::INVENTORY)
                            ->where('is_scrap', false)
                            ->first();

                        $currentQuantity = $state - $previousQuantity;

                        if ($currentQuantity < 0) {
                            $sourceLocationId = $record->location_id;

                            $destinationLocationId = $adjustmentLocation->id;
                        } else {
                            $sourceLocationId = $adjustmentLocation->id;

                            $destinationLocationId = $record->location_id;
                        }

                        $this->createMove($record, $currentQuantity, $sourceLocationId, $destinationLocationId);
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->success()
                            ->title(__('projects::filament/resources/task.table.actions.delete.notification.title'))
                            ->body(__('projects::filament/resources/task.table.actions.delete.notification.body'))
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/lot/pages/manage-quantities.table.actions.delete.notification.body')),
                    ),
            ])
            ->paginated(false);
    }

    private function createMove($record, $currentQuantity, $sourceLocationId, $destinationLocationId)
    {
        $move = Move::create([
            'name'                    => 'Product Quantity Updated',
            'state'                   => Enums\MoveState::DONE,
            'product_id'              => $record->product_id,
            'source_location_id'      => $sourceLocationId,
            'destination_location_id' => $destinationLocationId,
            'requested_qty'           => abs($currentQuantity),
            'requested_uom_qty'       => abs($currentQuantity),
            'received_qty'            => abs($currentQuantity),
            'reference'               => 'Product Quantity Updated',
            'creator_id'              => Auth::id(),
            'company_id'              => $record->company_id,
        ]);

        $move->lines()->create([
            'state'                   => Enums\MoveState::DONE,
            'qty'                     => abs($currentQuantity),
            'uom_qty'                 => abs($currentQuantity),
            'is_picked'               => 1,
            'scheduled_at'            => now(),
            'operation_id'            => null,
            'product_id'              => $record->product_id,
            'lot_id'                  => $record->lot_id,
            'result_package_id'       => $record->package_id,
            'uom_id'                  => $record->product->uom_id,
            'source_location_id'      => $sourceLocationId,
            'destination_location_id' => $destinationLocationId,
            'reference'               => $move->reference,
            'company_id'              => $record->company_id,
            'creator_id'              => Auth::id(),
        ]);
    }
}
