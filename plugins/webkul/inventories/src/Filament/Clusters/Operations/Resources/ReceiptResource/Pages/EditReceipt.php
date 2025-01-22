<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Operation;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\ProductQuantity;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.notification.title'))
            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            Actions\Action::make('todo')
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.label'))
                ->requiresConfirmation()
                ->action(function (Operation $record) {
                    if (! $record->moves->count()) {
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.warning.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.warning.body'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $record->update(['state' => Enums\OperationState::READY]);

                    $record->moves()->update(['state' => Enums\MoveState::CONFIRMED]);

                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.success.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.success.body'))
                        ->success()
                        ->send();
                })
                ->hidden(fn () => $this->getRecord()->state !== Enums\OperationState::DRAFT),
            Actions\Action::make('validate')
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.validate.label'))
                ->color('gray')
                ->action(function (Operation $record) {
                    $record->update([
                        'state' => Enums\OperationState::DONE,
                    ]);

                    foreach ($record->moves as $move) {
                        $move->update([
                            'state'        => Enums\MoveState::DONE,
                            'is_picked'    => true,
                            'received_qty' => $move->received_qty > 0 ? $move->received_qty : $move->requested_qty,
                        ]);

                        $this->createOrUpdateMoveLines($move);

                        foreach ($move->lines()->get() as $moveLine) {
                            $productQuantity = ProductQuantity::where('product_id', $moveLine->product_id)
                                ->where('location_id', $moveLine->destination_location_id)
                                ->where('package_id', $moveLine->result_package_id)
                                ->first();

                            if ($productQuantity) {
                                $productQuantity->increment('quantity', $moveLine->qty);
                            } else {
                                ProductQuantity::create([
                                    'product_id'        => $moveLine->product_id,
                                    'location_id'       => $moveLine->destination_location_id,
                                    'package_id'        => $moveLine->result_package_id,
                                    'lot_id'            => $moveLine->lot_id,
                                    'quantity'          => $move->received_qty,
                                    'reserved_quantity' => 0,
                                    'incoming_at'       => now(),
                                    'creator_id'        => $move->creator_id,
                                    'company_id'        => $move->company_id,
                                ]);
                            }
                        }
                    }
                })
                ->hidden(fn () => $this->getRecord()->state == Enums\OperationState::DONE),
            Actions\Action::make('return')
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.return.label'))
                ->color('gray'),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.body')),
                ),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        foreach ($record->moves as $move) {
            $product = Product::find($move->product_id);

            $move->fill([
                'name'                    => $product->name,
                'uom_id'                  => $move->uom_id ?? $product->uom_id,
                'requested_uom_qty'       => $move->requested_qty,
                'operation_type_id'       => $record->operation_type_id,
                'source_location_id'      => $record->source_location_id,
                'destination_location_id' => $record->destination_location_id,
                'scheduled_at'            => $record->scheduled_at ?? now(),
                'reference'               => $record->name,
            ]);

            if ($move->received_qty <= 0) {
                if ($record->state === Enums\OperationState::READY) {
                    $move->update(['state' => Enums\MoveState::CONFIRMED]);
                } else {
                    $move->update(['state' => Enums\MoveState::DRAFT]);
                }

                $move->lines()->delete();

                continue;
            }

            $move->update(['state' => Enums\MoveState::ASSIGNED]);

            $this->createOrUpdateMoveLines($move);
        }
    }

    public function createOrUpdateMoveLines(Move $move)
    {
        $lines = $move->lines()->orderBy('created_at')->get();

        if ($lines->isEmpty()) {
            $move->lines()->create([
                'lot_name'                => null,
                'state'                   => $move->state,
                'reference'               => $move->reference,
                'picking_description'     => $move->description_picking,
                'qty'                     => $move->received_qty,
                'uom_qty'                 => $move->requested_uom_qty,
                'is_picked'               => $move->is_picked,
                'scheduled_at'            => $move->scheduled_at,
                'operation_id'            => $move->operation_id,
                'product_id'              => $move->product_id,
                'uom_id'                  => $move->uom_id,
                'source_location_id'      => $move->source_location_id,
                'destination_location_id' => $move->destination_location_id,
                'company_id'              => $move->company_id,
                'creator_id'              => $move->creator_id,
            ]);
        }

        $remainingQty = $move->received_qty;

        $linesToKeep = collect();

        foreach ($lines as $line) {
            if ($remainingQty > 0) {
                $newQty = min($line->qty, $remainingQty);

                $linesToKeep->push([
                    'state' => $move->state,
                    'line' => $line,
                    'new_qty' => $newQty
                ]);

                $remainingQty -= $newQty;
            }
        }

        $lines->each(function ($line) use ($linesToKeep, $move) {
            $lineToKeep = $linesToKeep->firstWhere('line.id', $line->id);

            if ($lineToKeep) {
                $line->update([
                    'state' => $move->state,
                    'qty' => $lineToKeep['new_qty'],
                    'uom_qty' => $lineToKeep['new_qty'],
                ]);
            } else {
                $line->delete();
            }
        });
    }

    public function haveSufficientQty(Operation $record): bool
    {
        foreach ($record->moves as $move) {
            if ($move->product->quantities->sum('qty') < $move->requested_qty) {
                return false;
            }
        }

        return true;
    }
}
