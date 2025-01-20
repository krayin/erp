<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\MoveResource;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Enums;

class MovesRelationManager extends RelationManager
{
    protected static string $relationship = 'moves';

    public function form(Form $form): Form
    {
        return MoveResource::form($form);
    }

    public function table(Table $table): Table
    {
        return MoveResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $product = Product::find($data['product_id']);
                        
                        $data['name'] = $product->name;
                        
                        $data['procure_method'] = Enums\ProcureMethod::MAKE_TO_STOCK;

                        $data['state'] = Enums\MoveState::DRAFT;

                        $data['uom_id'] = $product->uom_id;

                        $data['product_uom_qty'] = $data['product_qty'];

                        // $data['qty'] = $data['product_qty'];

                        $data['source_location_id'] = $this->getOwnerRecord()->source_location_id;

                        $data['destination_location_id'] = $this->getOwnerRecord()->destination_location_id;
                        
                        $data['uom_id'] = $product->uom_id;

                        $data['scheduled_at'] = $this->getOwnerRecord()->scheduled_at;

                        $data['reference'] = $this->getOwnerRecord()->name;

                        $data['creator_id'] = Auth::id();

                        $data['company_id'] = $this->getOwnerRecord()->company_id ?? Auth::user()->default_company_id;

                        return $data;
                    })
                    // ->after(function (Move $move) {
                    //     //create move line
                    //     $moveLine = MoveLine::create([
                    //         'move_id' => $move->id,
                    //         'state' => Enums\MoveState::DRAFT,
                    //         'reference' => $move->reference,
                    //         'quantity' => $move->product_qty,
                    //         'quantity_product_uom' => $move->product_uom_qty,
                    //         'is_picked' => false,
                    //         'scheduled_at' => $move->scheduled_at,
                    //         'product_id' => $move->product_id,
                    //         'uom_id' => $move->uom_id,
                    //         'source_location_id' => $move->source_location_id,
                    //         'destination_location_id' => $move->destination_location_id,
                    //         'company_id' => $move->company_id,
                    //         'creator_id' => $move->creator_id,
                    //     ]);
                    // })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->before(function (Move $record) {
                        $record->operation->update(['state' => Enums\OperationState::DRAFT]);

                        $record->operation->moves()->update(['state' => Enums\MoveState::DRAFT]);

                        $record->operation->moveLines()->update(['state' => Enums\MoveState::DRAFT]);
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.actions.delete.notification.body')),
                    ),
            ]);
    }
}
