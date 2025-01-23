<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\CreateBackorder;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Enums\MoveType;
use Webkul\Inventory\Enums\OperationType as OperationTypeEnum;
use Webkul\Inventory\Enums\ReservationMethod;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource\Pages;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Models\OperationType;
use Webkul\Inventory\Models\Warehouse;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;

class OperationTypeResource extends Resource
{
    protected static ?string $model = OperationType::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/operation-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/operation-type.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.sections.general.fields.operator-type'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->placeholder(__('inventories::filament/clusters/configurations/resources/operation-type.form.sections.general.fields.operator-type-placeholder'))
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                    ]),

                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.title'))
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('type')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.operator-type'))
                                                    ->required()
                                                    ->options(OperationTypeEnum::class)
                                                    ->default(OperationTypeEnum::INCOMING->value)
                                                    ->native(true)
                                                    ->live()
                                                    ->selectablePlaceholder(false)
                                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                                        // Clear existing values
                                                        $set('print_label', null);

                                                        // Get the new default values based on current type
                                                        $type = $get('type');
                                                        $warehouseId = $get('warehouse_id');

                                                        // Set new source location
                                                        $sourceLocationId = match ($type) {
                                                            OperationTypeEnum::INCOMING => Location::where('type', LocationType::SUPPLIER->value)->first()?->id,
                                                            OperationTypeEnum::OUTGOING => Location::where('is_replenish', 1)
                                                                ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                                ->first()?->id,
                                                            OperationTypeEnum::INTERNAL => Location::where('is_replenish', 1)
                                                                ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                                ->first()?->id,
                                                            default => null,
                                                        };

                                                        // Set new destination location
                                                        $destinationLocationId = match ($type) {
                                                            OperationTypeEnum::INCOMING => Location::where('is_replenish', 1)
                                                                ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                                ->first()?->id,
                                                            OperationTypeEnum::OUTGOING => Location::where('type', LocationType::CUSTOMER->value)->first()?->id,
                                                            OperationTypeEnum::INTERNAL => Location::where('is_replenish', 1)
                                                                ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                                ->first()?->id,
                                                            default => null,
                                                        };

                                                        // Set the new values
                                                        $set('source_location_id', $sourceLocationId);
                                                        $set('destination_location_id', $destinationLocationId);
                                                    }),
                                                Forms\Components\TextInput::make('sequence_code')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.sequence-prefix'))
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\Toggle::make('print_label')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.generate-shipping-labels'))
                                                    ->inline(false)
                                                    ->visible(fn (Forms\Get $get): bool => in_array($get('type'), [OperationTypeEnum::OUTGOING->value, OperationTypeEnum::INTERNAL->value])),
                                                Forms\Components\Select::make('warehouse_id')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.warehouse'))
                                                    ->relationship('warehouse', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->default(function (Forms\Get $get) {
                                                        return Warehouse::first()?->id;
                                                    }),
                                                Forms\Components\Radio::make('reservation_method')
                                                    ->required()
                                                    ->options(ReservationMethod::class)
                                                    ->default(ReservationMethod::AT_CONFIRM->value)
                                                    ->visible(fn (Forms\Get $get): bool => $get('type') != OperationTypeEnum::INCOMING->value),
                                                Forms\Components\Toggle::make('auto_show_reception_report')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.show-reception-report'))
                                                    ->inline(false)
                                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.show-reception-report-hint-tooltip'))
                                                    ->visible(fn (OperationSettings $operationSettings, Forms\Get $get): bool => $operationSettings->enable_reception_report && in_array($get('type'), [OperationTypeEnum::INCOMING->value, OperationTypeEnum::INTERNAL->value])),
                                            ]),

                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('company_id')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.company'))
                                                    ->relationship('company', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(Auth::user()->default_company_id),
                                                Forms\Components\Select::make('return_operation_type_id')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.return-type'))
                                                    ->relationship('returnOperationType', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->visible(fn (Forms\Get $get): bool => $get('type') != OperationTypeEnum::DROPSHIP->value),
                                                Forms\Components\Select::make('create_backorder')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.create-backorder'))
                                                    ->required()
                                                    ->options(CreateBackorder::class)
                                                    ->default(CreateBackorder::ASK->value),
                                                Forms\Components\Select::make('move_type')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.move-type'))
                                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fields.move-type-hint-tooltip'))
                                                    ->options(MoveType::class)
                                                    ->visible(fn (Forms\Get $get): bool => $get('type') == OperationTypeEnum::INTERNAL->value),
                                            ]),
                                    ])
                                    ->columns(2),
                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.title'))
                                    ->schema([
                                        Forms\Components\Toggle::make('use_create_lots')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.fields.create-new'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.fields.create-new-hint-tooltip'))
                                            ->inline(false),
                                        Forms\Components\Toggle::make('use_existing_lots')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.fields.use-existing'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.fields.use-existing-hint-tooltip'))
                                            ->inline(false),
                                    ])
                                    ->visible(fn (TraceabilitySettings $traceabilitySettings, Forms\Get $get): bool => $traceabilitySettings->enable_lots_serial_numbers && $get('type') != OperationTypeEnum::DROPSHIP->value),
                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.locations.title'))
                                    ->schema([
                                        Forms\Components\Select::make('source_location_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.locations.fields.source-location'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.fields.source-location-hint-tooltip'))
                                            ->relationship('sourceLocation', 'full_name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->default(function (Forms\Get $get) {
                                                $type = $get('type');

                                                $warehouseId = $get('warehouse_id');

                                                return match ($type) {
                                                    OperationTypeEnum::INCOMING => Location::where('type', LocationType::SUPPLIER->value)->first()?->id,
                                                    OperationTypeEnum::OUTGOING => Location::where('is_replenish', 1)
                                                        ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                        ->first()?->id,
                                                    OperationTypeEnum::INTERNAL => Location::where('is_replenish', 1)
                                                        ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                        ->first()?->id,
                                                    default => null,
                                                };
                                            })
                                            ->live(),
                                        Forms\Components\Select::make('destination_location_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.locations.fields.destination-location'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.lots.fields.destination-location-hint-tooltip'))
                                            ->relationship('destinationLocation', 'full_name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->default(function (Forms\Get $get) {
                                                $type = $get('type');
                                                $warehouseId = $get('warehouse_id');

                                                return match ($type) {
                                                    OperationTypeEnum::INCOMING => Location::where('is_replenish', 1)
                                                        ->when($warehouseId, fn ($query) => $query->where('warehouse_id', $warehouseId))
                                                        ->first()?->id,
                                                    OperationTypeEnum::OUTGOING => Location::where('type', LocationType::CUSTOMER->value)->first()?->id,
                                                    OperationTypeEnum::INTERNAL => Location::where(function ($query) use ($warehouseId) {
                                                        $query->whereNull('warehouse_id')
                                                            ->when($warehouseId, fn ($q) => $q->orWhere('warehouse_id', $warehouseId));
                                                    })->first()?->id,
                                                    default => null,
                                                };
                                            }),
                                    ])
                                    ->visible(fn (WarehouseSettings $warehouseSettings): bool => $warehouseSettings->enable_locations),
                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.packages.title'))
                                    ->schema([
                                        Forms\Components\Toggle::make('show_entire_packs')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.packages.fields.show-entire-package'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.general.fieldsets.packages.fields.show-entire-package-hint-tooltip'))
                                            ->inline(false),
                                    ])
                                    ->visible(fn (OperationSettings $operationSettings, Forms\Get $get): bool => $operationSettings->enable_packages && $get('type') != OperationTypeEnum::DROPSHIP->value),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.title'))
                            ->icon('heroicon-o-computer-desktop')
                            ->schema([
                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.title'))
                                    ->schema([
                                        Forms\Components\Toggle::make('auto_print_delivery_slip')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.delivery-slip'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.delivery-slip-hint-tooltip'))
                                            ->inline(false),
                                        Forms\Components\Toggle::make('auto_print_return_slip')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.return-slip'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.return-slip-hint-tooltip'))
                                            ->inline(false),
                                        Forms\Components\Toggle::make('auto_print_product_labels')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.product-labels'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.product-labels-hint-tooltip'))
                                            ->inline(false),
                                        Forms\Components\Toggle::make('auto_print_lot_labels')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.lots-labels'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.lots-labels-hint-tooltip'))
                                            ->inline(false)
                                            ->visible(fn (TraceabilitySettings $traceabilitySettings): bool => $traceabilitySettings->enable_lots_serial_numbers),
                                        Forms\Components\Toggle::make('auto_print_reception_report')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.reception-report'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.reception-report-hint-tooltip'))
                                            ->inline(false)
                                            ->visible(fn (OperationSettings $operationSettings): bool => $operationSettings->enable_reception_report),
                                        Forms\Components\Toggle::make('auto_print_reception_report_labels')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.reception-report-labels'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.reception-report-labels-hint-tooltip'))
                                            ->inline(false)
                                            ->visible(fn (OperationSettings $operationSettings): bool => $operationSettings->enable_reception_report),
                                        Forms\Components\Toggle::make('auto_print_packages')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.package-content'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-validation.fields.package-content-hint-tooltip'))
                                            ->inline(false)
                                            ->visible(fn (OperationSettings $operationSettings): bool => $operationSettings->enable_packages),
                                    ])
                                    ->columns(2),

                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-pack.title'))
                                    ->schema([
                                        Forms\Components\Toggle::make('auto_print_package_label')
                                            ->label(__('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-pack.fields.package-label'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/operation-type.form.tabs.hardware.fieldsets.print-on-pack.fields.package-label-hint-tooltip'))
                                            ->inline(false),
                                    ])
                                    ->visible(fn (OperationSettings $operationSettings): bool => $operationSettings->enable_packages),
                            ])
                            ->visible(fn (Forms\Get $get): bool => $get('type') != OperationTypeEnum::DROPSHIP->value),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.columns.company'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.columns.warehouse'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('warehouse.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.groups.warehouse'))
                    ->collapsible(),
                Tables\Grouping\Group::make('type')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.groups.type'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/operation-type.table.actions.restore.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/operation-type.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/operation-type.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/operation-type.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/operation-type.table.actions.force-delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/operation-type.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/operation-type.table.bulk-actions.restore.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/operation-type.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/operation-type.table.bulk-actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/operation-type.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/operation-type.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/operation-type.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/configurations/resources/operation-type.table.empty-actions.create.label'))
                    ->icon('heroicon-o-plus-circle'),
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
            'index'  => Pages\ListOperationTypes::route('/'),
            'create' => Pages\CreateOperationType::route('/create'),
            'view'   => Pages\ViewOperationType::route('/{record}'),
            'edit'   => Pages\EditOperationType::route('/{record}/edit'),
        ];
    }
}
