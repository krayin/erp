<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Product\Enums\AttributeType;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.form.fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.form.fields.color'))
                    ->required()
                    ->hidden($this->getOwnerRecord()->type != AttributeType::COLOR),
                Forms\Components\TextInput::make('extra_price')
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.form.fields.extra-price'))
                    ->required()
                    ->numeric()
                    ->default(0.0000)
                    ->minValue(0),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.columns.name')),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.columns.color'))
                    ->hidden($this->getOwnerRecord()->type != AttributeType::COLOR),
                Tables\Columns\TextColumn::make('extra_price')
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.columns.extra-price')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        $ownerRecord = $this->getOwnerRecord();

                        $data['project_id'] = $ownerRecord->project_id;

                        $data['partner_id'] = $ownerRecord->partner_id ?? $ownerRecord->project?->partner_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.actions.edit.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/product-attribute/relation-managers/options.table.actions.delete.notification.body')),
                    ),
            ])
            ->paginated(false);
    }
}
