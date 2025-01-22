<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\DeliveryResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\MoveResource;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DeliveryResource;
use Webkul\Inventory\Models\Product;

class ManageMoves extends ManageRelatedRecords
{
    protected static string $resource = DeliveryResource::class;

    protected static string $relationship = 'moves';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

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
