<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Models\Operation;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Inventory\Enums\OperationState;
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
                        ProgressStepper::make('stage_id')
                            ->hiddenLabel()
                            ->inline()
                            ->options(OperationState::options())
                            ->default(OperationState::DRAFT),
                            // ->disableOptionWhen(fn (string $value): bool => true),
                        Forms\Components\Section::make(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.title'))
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Group::make()
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
                                                    ->live(),
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
                                            ]),

                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\DateTimePicker::make('scheduled_at')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.scheduled-at'))
                                                    ->native(false)
                                                    ->default(now()->format('Y-m-d H:i:s'))
                                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.scheduled-at-hint-tooltip')),
                                                Forms\Components\TextInput::make('origin')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.source-document'))
                                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.source-document-hint-tooltip')),
                                            ]),
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
