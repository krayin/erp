<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\ProductQuantity;
use Webkul\Inventory\Models\Warehouse;
use Webkul\Inventory\Settings\WarehouseSettings;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/products/resources/product/pages/edit-product.notification.title'))
            ->body(__('inventories::filament/clusters/products/resources/product/pages/edit-product.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            Actions\Action::make('updateQuantity')
                ->label(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.label'))
                ->modalHeading(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.modal-heading'))
                ->form(fn (Product $record): array => [
                    Forms\Components\TextInput::make('quantity')
                        ->label(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.form.fields.on-hand-qty'))
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->default($record->quantities()->sum('quantity')),
                ])
                ->modalSubmitActionLabel(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.update-quantity.modal-submit-action-label'))
                ->beforeFormFilled(function (WarehouseSettings $warehouseSettings, Product $record) {
                    if ($warehouseSettings->enable_locations) {
                        return redirect()->to(ProductResource::getUrl('quantities', ['record' => $record]));
                    }
                })
                ->action(function (Product $record, array $data): void {
                    $previousQuantity = $record->quantities()->sum('quantity');

                    if ($previousQuantity == $data['quantity']) {
                        return;
                    }

                    $warehouse = Warehouse::first();

                    $adjustmentLocation = Location::where('type', Enums\LocationType::INVENTORY)
                        ->where('is_scrap', false)
                        ->first();

                    $currentQuantity = $data['quantity'] - $previousQuantity;

                    if ($currentQuantity < 0) {
                        $sourceLocationId = $data['location_id'] ?? $warehouse->lot_stock_location_id;

                        $destinationLocationId = $adjustmentLocation->id;
                    } else {
                        $sourceLocationId = $data['location_id'] ?? $adjustmentLocation->id;

                        $destinationLocationId = $warehouse->lot_stock_location_id;
                    }

                    $move = Move::create([
                        'name'                    => 'Product Quantity Updated',
                        'state'                   => Enums\MoveState::DONE,
                        'product_id'              => $record->id,
                        'source_location_id'      => $sourceLocationId,
                        'destination_location_id' => $destinationLocationId,
                        'requested_qty'           => abs($currentQuantity),
                        'requested_uom_qty'       => abs($currentQuantity),
                        'received_qty'            => abs($currentQuantity),
                        'package_id'              => $data['package_id'] ?? null,
                        'lot_id'                  => $data['lot_id'] ?? null,
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
                        'product_id'              => $record->id,
                        'uom_id'                  => $record->uom_id,
                        'source_location_id'      => $sourceLocationId,
                        'destination_location_id' => $destinationLocationId,
                        'reference'               => $move->reference,
                        'company_id'              => $record->company_id,
                        'creator_id'              => Auth::id(),
                    ]);

                    $productQuantity = ProductQuantity::where('product_id', $record->id)
                        ->where('location_id', $data['location_id'] ?? $warehouse->lot_stock_location_id)
                        ->first();

                    if ($productQuantity) {
                        $productQuantity->update(['quantity' => $data['quantity']]);
                    } else {
                        ProductQuantity::create([
                            'product_id'        => $record->id->product_id,
                            'location_id'       => $data['location_id'] ?? $warehouse->lot_stock_location_id,
                            'package_id'        => $data['package_id'] ?? null,
                            'lot_id'            => $data['lot_id'] ?? null,
                            'quantity'          => $data['quantity'],
                            'reserved_quantity' => 0,
                            'incoming_at'       => now(),
                            'creator_id'        => Auth::id(),
                        ]);
                    }
                }),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/products/resources/product/pages/edit-product.header-actions.delete.notification.body')),
                ),
        ];
    }
}
