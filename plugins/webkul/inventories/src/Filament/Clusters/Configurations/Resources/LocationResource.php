<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\LocationResource\Pages;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages\ManageLocations;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Product\Enums\ProductRemoval;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(WarehouseSettings::class)->enable_locations;
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/location.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/location.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/location.form.sections.general.title'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.general.fields.location'))
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder(__('inventories::filament/clusters/configurations/resources/location.form.sections.general.fields.location-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                                Forms\Components\Select::make('parent_id')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.general.fields.parent-location'))
                                    ->relationship('parent', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.general.fields.parent-location-hint-tooltip')),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.general.fields.external-notes')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.title'))
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.location-type'))
                                    ->options(LocationType::class)
                                    ->selectablePlaceholder(false)
                                    ->required()
                                    ->default(LocationType::INTERNAL->value)
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                        if (! $get('type') === in_array($get('type'), [LocationType::INTERNAL->value, LocationType::INVENTORY->value])) {
                                            $set('is_scrap', false);
                                        }

                                        if ($get('type') !== LocationType::INTERNAL->value) {
                                            $set('storage_category_id', null);

                                            $set('is_replenish', false);
                                        }
                                    }),
                                Forms\Components\Select::make('company_id')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.company'))
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::user()->default_company_id),
                                Forms\Components\Select::make('storage_category_id')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.storage-category'))
                                    ->relationship('storageCategory', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Forms\Get $get): bool => $get('type') === LocationType::INTERNAL->value)
                                    ->hiddenOn(ManageLocations::class),
                                Forms\Components\Toggle::make('is_scrap')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.is-scrap'))
                                    ->inline(false)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.is-scrap-hint-tooltip'))
                                    ->visible(fn (Forms\Get $get): bool => in_array($get('type'), [LocationType::INTERNAL->value, LocationType::INVENTORY->value]))
                                    ->live(),

                                Forms\Components\Toggle::make('is_dock')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.is-dock'))
                                    ->inline(false)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.is-dock-hint-tooltip')),

                                Forms\Components\Toggle::make('is_replenish')
                                    ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.is-replenish'))
                                    ->inline(false)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.is-replenish-hint-tooltip'))
                                    ->visible(fn (Forms\Get $get): bool => $get('type') === LocationType::INTERNAL->value),

                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.logistics'))
                                    ->schema([
                                        Forms\Components\Radio::make('removal_strategy')
                                            ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.removal-strategy'))
                                            ->options(ProductRemoval::class)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.removal-strategy-hint-tooltip')),
                                    ])
                                    ->columns(1)
                                    ->visible(fn (Forms\Get $get): bool => in_array($get('type'), [LocationType::VIEW->value, LocationType::INTERNAL->value, LocationType::TRANSIT->value]) && ! $get('is_scrap')),

                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.cyclic-counting'))
                                    ->schema([
                                        Forms\Components\TextInput::make('cyclic_inventory_frequency')
                                            ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.inventory-frequency'))
                                            ->integer(),
                                        Forms\Components\DatePicker::make('cyclic_inventory_frequency')
                                            ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.last-inventory'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.last-inventory-hint-tooltip'))
                                            ->native(false)
                                            ->readOnly()
                                            ->disabled(),
                                        Forms\Components\DatePicker::make('cyclic_inventory_frequency')
                                            ->label(__('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.next-expected'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/location.form.sections.settings.fields.next-expected-hint-tooltip'))
                                            ->native(false)
                                            ->readOnly()
                                            ->disabled(),
                                    ])
                                    ->visible(fn (Forms\Get $get): bool => in_array($get('type'), [LocationType::INTERNAL->value, LocationType::TRANSIT->value]))
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.location'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('storageCategory.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.storage-category'))
                    ->numeric()
                    ->sortable()
                    ->hiddenOn(ManageLocations::class),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.deleted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('warehouse.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.groups.warehouse'))
                    ->collapsible(),
                Tables\Grouping\Group::make('type')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.groups.type'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/location.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->modalWidth('6xl'),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->modalWidth('6xl')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/location.table.actions.edit.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/location.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/location.table.actions.restore.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/location.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/location.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/location.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/location.table.actions.force-delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/location.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/location.table.bulk-actions.restore.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/location.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/location.table.bulk-actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/location.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/location.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/location.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        $currentRoute = request()->route()?->getName();

        if ($currentRoute === self::getRouteBaseName().'.index') {
            return SubNavigationPosition::Start;
        }

        return SubNavigationPosition::Top;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewLocation::class,
            Pages\EditLocation::class,
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
            'index'  => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view'   => Pages\ViewLocation::route('/{record}'),
            'edit'   => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
