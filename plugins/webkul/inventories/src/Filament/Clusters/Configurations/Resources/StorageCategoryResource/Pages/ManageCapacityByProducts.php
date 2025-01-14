<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource;

class ManageCapacityByProducts extends ManageRelatedRecords
{
    protected static string $resource = StorageCategoryResource::class;

    protected static string $relationship = 'storageCategoryCapacitiesByProduct';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.form.product'))
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('qty')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.form.qty'))
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.columns.product')),
                Tables\Columns\TextColumn::make('qty')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.columns.qty')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.actions.edit.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-capacity-by-products.table.actions.delete.notification.body')),
                    ),
            ])
            ->paginated(false);
    }
}
