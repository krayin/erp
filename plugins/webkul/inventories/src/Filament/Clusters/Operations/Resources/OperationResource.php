<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Operation;
use Webkul\Inventory\Models\OperationType;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Settings\ProductSettings;
use Webkul\Inventory\Settings\WarehouseSettings;

class OperationResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Operation::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ProgressStepper::make('state')
                    ->hiddenLabel()
                    ->inline()
                    ->options(Enums\OperationState::options())
                    ->default(Enums\OperationState::DRAFT)
                    ->disabled(),
                Forms\Components\Section::make(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.title'))
                    ->schema([
                        Forms\Components\Hidden::make('move_type')
                            ->default(Enums\MoveType::DIRECT),
                        Forms\Components\Select::make('partner_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.receive-from'))
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type == Enums\OperationType::INCOMING)
                            ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                        Forms\Components\Select::make('partner_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.contact'))
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type == Enums\OperationType::INTERNAL)
                            ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                        Forms\Components\Select::make('partner_address_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.delivery-address'))
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type == Enums\OperationType::OUTGOING)
                            ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                        Forms\Components\Select::make('operation_type_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.operation-type'))
                            ->relationship('operationType', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                $operationType = OperationType::find($get('operation_type_id'));

                                $set('source_location_id', $operationType->source_location_id);
                                $set('destination_location_id', $operationType->destination_location_id);
                            })
                            ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                        Forms\Components\Select::make('source_location_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.source-location'))
                            ->relationship('sourceLocation', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (WarehouseSettings $warehouseSettings): bool => $warehouseSettings->enable_locations)
                            ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                        Forms\Components\Select::make('destination_location_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.destination-location'))
                            ->relationship('destinationLocation', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (WarehouseSettings $warehouseSettings): bool => $warehouseSettings->enable_locations)
                            ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                    ])
                    ->columns(2),

                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.title'))
                            ->schema([
                                static::getMovesRepeater(),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.title'))
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.responsible'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::id())
                                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                                Forms\Components\Select::make('move_type')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.shipping-policy'))
                                    ->options(Enums\MoveType::class)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.shipping-policy-hint-tooltip'))
                                    ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type != Enums\OperationType::INCOMING)
                                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                                Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.scheduled-at'))
                                    ->native(false)
                                    ->default(now()->format('Y-m-d H:i:s'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.scheduled-at-hint-tooltip'))
                                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                                Forms\Components\TextInput::make('origin')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.source-document'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.source-document-hint-tooltip'))
                                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED])),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make(__('inventories::filament/clusters/operations/resources/operation.form.tabs.note.title'))
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->hiddenLabel(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_favorite')
                    ->label('')
                    ->icon(fn (Operation $record): string => $record->is_favorite ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Operation $record): string => $record->is_favorite ? 'warning' : 'gray')
                    ->action(function (Operation $record): void {
                        $record->update([
                            'is_favorite' => ! $record->is_favorite,
                        ]);
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sourceLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.from'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (WarehouseSettings $warehouseSettings): bool => $warehouseSettings->enable_locations),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.to'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (WarehouseSettings $warehouseSettings): bool => $warehouseSettings->enable_locations),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.contact'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.responsible'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.scheduled-at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.deadline'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('closed_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.closed-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('origin')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.source-document'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('operationType.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.operation-type'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.state'))
                    ->searchable()
                    ->sortable()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2);
    }

    public static function getMovesRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('moves')
            ->hiddenLabel()
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.product'))
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        if ($product = Product::find($get('product_id'))) {
                            $set('product_packaging_id', $product->product_packaging_id);

                            $set('uom_id', $product->uom_id);
                        }
                    })
                    ->disabled(fn (Move $move): bool => $move->id && $move->state !== Enums\MoveState::DRAFT),
                Forms\Components\Select::make('final_location_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.final-location'))
                    ->relationship('finalLocation', 'full_name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (WarehouseSettings $warehouseSettings) => $warehouseSettings->enable_locations)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
                Forms\Components\TextInput::make('description')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.description'))
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.scheduled-at'))
                    ->default(now())
                    ->native(false)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
                Forms\Components\DateTimePicker::make('deadline')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.deadline'))
                    ->native(false)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
                Forms\Components\Select::make('product_packaging_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.packaging'))
                    ->relationship('productPackaging', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (ProductSettings $productSettings) => $productSettings->enable_packagings)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
                Forms\Components\TextInput::make('requested_qty')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.demand'))
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required()
                    ->disabled(fn (Move $move): bool => $move->id && $move->state !== Enums\MoveState::DRAFT),
                Forms\Components\TextInput::make('received_qty')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.quantity'))
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required()
                    ->visible(fn (Move $move): bool => $move->id && $move->state !== Enums\MoveState::DRAFT)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED]))
                    ->suffixAction(static::getMoveLinesAction()),
                Forms\Components\Select::make('uom_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.unit'))
                    ->relationship(
                        'uom',
                        'name',
                        fn ($query) => $query->where('category_id', 1),
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn (ProductSettings $productSettings) => $productSettings->enable_uom)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
                Forms\Components\Toggle::make('is_picked')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.picked'))
                    ->default(0)
                    ->inline(false)
                    ->disabled(fn ($record): bool => in_array($record?->state, [Enums\MoveState::DONE, Enums\OperationState::CANCELED])),
            ])
            ->columns(4)
            ->mutateRelationshipDataBeforeCreateUsing(function (array $data) {
                $data['creator_id'] = Auth::id();

                $data['company_id'] = Auth::user()->default_company_id;

                return $data;
            })
            ->deletable(fn ($record): bool => ! in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED]))
            ->addable(fn ($record): bool => ! in_array($record?->state, [Enums\OperationState::DONE, Enums\OperationState::CANCELED]));
    }

    public static function getMoveLinesAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('manageLines')
            ->icon('heroicon-m-bars-4')
            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.modal-heading'))
            ->modalSubmitActionLabel('Save')
            ->form([
                Forms\Components\Repeater::make('lines')
                    ->hiddenLabel()
                    ->relationship('lines')
                    ->schema([
                        Forms\Components\Select::make('destination_location_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.location'))
                            ->relationship('destinationLocation', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('result_package_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.package'))
                            ->relationship('resultPackage', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('qty')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.quantity'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Add Line')
                    ->columns(3)
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Move $move): array {
                        $data['reference'] = $move->reference;
                        $data['state'] = $move->state;
                        $data['uom_qty'] = $data['qty'];
                        $data['scheduled_at'] = $move->scheduled_at;
                        $data['operation_id'] = $move->operation_id;
                        $data['move_id'] = $move->id;
                        $data['source_location_id'] = $move->source_location_id;
                        $data['uom_id'] = $move->uom_id;
                        $data['creator_id'] = Auth::id();
                        $data['product_id'] = $move->product_id;
                        $data['company_id'] = $move->company_id;

                        return $data;
                    })
            ])
            ->modalWidth('6xl')
            ->mountUsing(function (Forms\ComponentContainer $form, Move $record) {
                $form->fill([
                    'lines' => $record->lines()->get()->map(function ($line) {
                        return [
                            'id' => $line->id,
                            'destination_location_id' => $line->destination_location_id,
                            'result_package_id' => $line->result_package_id,
                            'qty' => $line->qty,
                        ];
                    })->toArray()
                ]);
            })
            ->action(function (Forms\Set $set, array $data, Move $record): void {
                $totalQty = $record->lines()->sum('qty');

                $record->update([
                    'received_qty' => $totalQty,
                ]);

                $set('received_qty', $totalQty);
            });
    }
}
