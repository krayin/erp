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
                        $data['name'] = Product::find($data['product_id'])->name;
                        
                        $data['procure_method'] = Enums\ProcureMethod::MAKE_TO_STOCK;

                        $data['source_location_id'] = $this->getOwnerRecord()->source_location_id;

                        $data['destination_location_id'] = $this->getOwnerRecord()->destination_location_id;

                        $data['scheduled_at'] = $this->getOwnerRecord()->scheduled_at;

                        $data['reference'] = $this->getOwnerRecord()->name;

                        $data['creator_id'] = Auth::id();

                        $data['company_id'] = $this->getOwnerRecord()->company_id ?? Auth::user()->default_company_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/manage-moves.table.actions.delete.notification.body')),
                    ),
            ]);
    }
}
