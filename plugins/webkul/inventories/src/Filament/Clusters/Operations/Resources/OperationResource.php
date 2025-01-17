<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Models\Operation;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Inventory\Models\OperationType;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource\Pages;

class OperationResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Operation::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
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
                                    ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type == Enums\OperationType::INCOMING),
                                Forms\Components\Select::make('partner_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.contact'))
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type == Enums\OperationType::INTERNAL),
                                Forms\Components\Select::make('partner_address_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.delivery-address'))
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type == Enums\OperationType::OUTGOING),
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
                                    }),
                                Forms\Components\Select::make('source_location_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.source-location'))
                                    ->relationship('sourceLocation', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('destination_location_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.destination-location'))
                                    ->relationship('destinationLocation', 'full_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\RichEditor::make('description')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.external-notes'))
                                    ->columnSpan(2),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/operations/resources/operation.form.sections.additional.title'))
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.responsible'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::id()),
                                Forms\Components\Select::make('move_type')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.shipping-policy'))
                                    ->options(Enums\MoveType::class)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.shipping-policy-hint-tooltip'))
                                    ->visible(fn (Forms\Get $get): bool => OperationType::find($get('operation_type_id'))?->type != Enums\OperationType::INCOMING),
                                Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.scheduled-at'))
                                    ->native(false)
                                    ->default(now()->format('Y-m-d H:i:s'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.scheduled-at-hint-tooltip')),
                                Forms\Components\TextInput::make('origin')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.source-document'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.sections.additional.fields.source-document-hint-tooltip')),
                            ]),
                    ]),
            ])
            ->columns(3);
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
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.to'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
}
