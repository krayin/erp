<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Products;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;
use Webkul\Inventory\Models\Lot;
use Webkul\Inventory\Settings\TraceabilitySettings;

class LotResource extends Resource
{
    protected static ?string $model = Lot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Products::class;

    protected static ?int $navigationSort = 3;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(TraceabilitySettings::class)->enable_lots_serial_numbers;
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/lot.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/products/resources/lot.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->placeholder(__('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.name-placeholder'))
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label(__('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.product'))
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.product-hint-tooltip')),
                                Forms\Components\TextInput::make('reference')
                                    ->label(__('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.reference'))
                                    ->maxLength(255)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.reference-hint-tooltip')),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('inventories::filament/clusters/products/resources/lot.form.sections.general.fields.description'))
                                    ->columnSpan(2),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/products/resources/lot.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/products/resources/lot.table.columns.product'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->label(__('inventories::filament/clusters/products/resources/lot.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                //On hand quantity
                Tables\Columns\TextColumn::make('total_quantity')
                    ->label(__('inventories::filament/clusters/products/resources/lot.table.columns.on-hand-qty'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/products/resources/lot.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/products/resources/lot.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/products/resources/lot.table.actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/products/resources/lot.table.actions.delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/lot.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/lot.table.bulk-actions.delete.notification.body')),
                    ),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewLot::class,
            Pages\EditLot::class,
            Pages\ManageQuantities::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListLots::route('/'),
            'create'     => Pages\CreateLot::route('/create'),
            'view'       => Pages\ViewLot::route('/{record}'),
            'edit'       => Pages\EditLot::route('/{record}/edit'),
            'quantities' => Pages\ManageQuantities::route('/{record}/quantities'),
        ];
    }
}
