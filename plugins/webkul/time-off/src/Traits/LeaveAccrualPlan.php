<?php

namespace Webkul\TimeOff\Traits;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Support\Enums\Week;
use Webkul\TimeOff\Enums;
use Webkul\TimeOff\Models\LeaveAccrualLevel;

trait LeaveAccrualPlan
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('added_value')
                                    ->label('Accrual Amount')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(0)
                                    ->step(0.5),
                                Forms\Components\Select::make('added_value_type')
                                    ->label('Accrual Value Type')
                                    ->options(Enums\AddedValueType::class)
                                    ->default(Enums\AddedValueType::DAYS->value)
                                    ->required(),
                            ]),
                        Forms\Components\Fieldset::make()
                            ->label('Accrual Frequency')
                            ->schema([
                                Forms\Components\Select::make('frequency')
                                    ->label('')
                                    ->options(Enums\Frequency::class)
                                    ->live()
                                    ->default(Enums\Frequency::WEEKLY->value)
                                    ->required()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('week_day', null)),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('week_day')
                                                    ->label('Accrual Day')
                                                    ->options(Week::class)
                                                    ->default(Week::MONDAY->value)
                                                    ->required(),
                                            ])
                                            ->visible(fn (Get $get) => $get('frequency') === Enums\Frequency::WEEKLY->value),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('monthly_day')
                                                    ->label('Day of Month')
                                                    ->options(Enums\CarryoverDay::class)
                                                    ->default(Enums\CarryoverDay::DAY_1->value)
                                                    ->required(),
                                            ])
                                            ->visible(fn (Get $get) => $get('frequency') === Enums\Frequency::MONTHLY->value),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('first_day')
                                                    ->label('First Day of Month')
                                                    ->options(Enums\CarryoverDay::class)
                                                    ->default(Enums\CarryoverDay::DAY_1->value)
                                                    ->required(),
                                                Forms\Components\Select::make('second_day')
                                                    ->label('Second Day of Month')
                                                    ->options(Enums\CarryoverDay::class)
                                                    ->default(Enums\CarryoverDay::DAY_15->value)
                                                    ->required(),
                                            ])
                                            ->visible(fn (Get $get) => $get('frequency') === Enums\Frequency::BIMONTHLY->value),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Select::make('first_month')
                                                            ->label('First Period Month')
                                                            ->options(Enums\CarryoverMonth::class)
                                                            ->default(Enums\CarryoverMonth::JAN->value)
                                                            ->required(),
                                                        Forms\Components\Select::make('first_day_biyearly')
                                                            ->label('First Period Day')
                                                            ->options(Enums\CarryoverDay::class)
                                                            ->default(Enums\CarryoverDay::DAY_1->value)
                                                            ->required(),
                                                    ]),
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Select::make('second_month')
                                                            ->label('Second Period Month')
                                                            ->options(Enums\CarryoverMonth::class)
                                                            ->default(Enums\CarryoverMonth::JUL->value)
                                                            ->required(),
                                                        Forms\Components\Select::make('second_day_biyearly')
                                                            ->label('Second Period Day')
                                                            ->options(Enums\CarryoverDay::class)
                                                            ->default(Enums\CarryoverDay::DAY_1->value)
                                                            ->required(),
                                                    ]),
                                            ])
                                            ->visible(fn (Get $get) => $get('frequency') === Enums\Frequency::BIYEARLY->value),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Group::make()
                                                    ->schema([
                                                        Forms\Components\Select::make('first_day_biyearly')
                                                            ->label('First Period Day')
                                                            ->options(Enums\CarryoverDay::class)
                                                            ->default(Enums\CarryoverDay::DAY_1->value)
                                                            ->required(),
                                                        Forms\Components\Select::make('first_month')
                                                            ->label('First Period Year')
                                                            ->options(Enums\CarryoverMonth::class)
                                                            ->default(Enums\CarryoverMonth::JAN->value)
                                                            ->required(),
                                                    ]),
                                            ])
                                            ->visible(fn (Get $get) => $get('frequency') === Enums\Frequency::YEARLY->value),
                                    ]),
                            ]),
                        Forms\Components\Fieldset::make('Cap Accrued')
                            ->schema([
                                Forms\Components\Toggle::make('cap_accrued_time')
                                    ->inline(false)
                                    ->live()
                                    ->default(false)
                                    ->label('Cap accrued time'),
                                Forms\Components\TextInput::make('maximum_leave')
                                    ->label('Days')
                                    ->visible(fn (Get $get) => $get('cap_accrued_time') === true)
                                    ->numeric(),
                            ])->columns(4),
                        Forms\Components\Fieldset::make('Start Accrual')
                            ->schema([
                                Forms\Components\TextInput::make('start_count')
                                    ->live()
                                    ->default(1)
                                    ->label('Start Count'),
                                Forms\Components\Select::make('start_type')
                                    ->label('Start Type')
                                    ->options(Enums\StartType::class)
                                    ->default(Enums\StartType::YEARS->value)
                                    ->required()
                                    ->helperText('After allocation start date'),
                            ])->columns(2),
                        Forms\Components\Fieldset::make('Advanced Accrual Settings')
                            ->schema([
                                Forms\Components\Radio::make('action_with_unused_accruals')
                                    ->options(Enums\CarryOverUnusedAccruals::class)
                                    ->live()
                                    ->required()
                                    ->default(Enums\CarryOverUnusedAccruals::ALL_ACCRUED_TIME_CARRIED_OVER->value),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('cap_accrued_time_yearly')
                                            ->inline(false)
                                            ->live()
                                            ->visible(fn (Get $get) => $get('action_with_unused_accruals') == Enums\CarryOverUnusedAccruals::ALL_ACCRUED_TIME_CARRIED_OVER->value)
                                            ->default(false)
                                            ->label('Milestone cap'),
                                        Forms\Components\TextInput::make('maximum_leave_yearly')
                                            ->numeric()
                                            ->visible(fn (Get $get) => $get('cap_accrued_time_yearly'))
                                            ->label('Days'),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('accrual_validity')
                                            ->inline(false)
                                            ->live()
                                            ->visible(fn (Get $get) => $get('action_with_unused_accruals') == Enums\CarryOverUnusedAccruals::ALL_ACCRUED_TIME_CARRIED_OVER->value)
                                            ->default(false)
                                            ->label('Milestone cap'),
                                        Forms\Components\TextInput::make('accrual_validity_count')
                                            ->numeric()
                                            ->visible(fn (Get $get) => $get('accrual_validity'))
                                            ->label('Days'),
                                        Forms\Components\Select::make('accrual_validity_type')
                                            ->required()
                                            ->visible(fn (Get $get) => $get('accrual_validity'))
                                            ->options(Enums\AccrualValidityType::class)
                                            ->label('Days'),
                                    ]),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('added_value')
                    ->label('Accrual Amount')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('added_value_type')
                    ->label('Accrual Value Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('frequency')
                    ->label('Frequency')
                    ->sortable(),
                Tables\Columns\TextColumn::make('maximum_leave')
                    ->label('Max Leave Days')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('frequency')
                    ->options(\Webkul\TimeOff\Enums\Frequency::class)
                    ->label(__('Accrual Frequency')),
                SelectFilter::make('start_type')
                    ->options(\Webkul\TimeOff\Enums\StartType::class)
                    ->label(__('Start Type')),
                Tables\Filters\Filter::make('cap_accrued_time')
                    ->form([
                        Forms\Components\Toggle::make('cap_accrued_time')
                            ->label(__('Cap Accrued Time')),
                    ])
                    ->query(fn ($query, $data) => $query->where('cap_accrued_time', $data['cap_accrued_time']))
                    ->label(__('Cap Accrued Time')),
                SelectFilter::make('action_with_unused_accruals')
                    ->options(\Webkul\TimeOff\Enums\CarryOverUnusedAccruals::class)
                    ->label(__('Action with Unused Accruals')),
                QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('added_value')
                            ->label(__('Accrual Amount'))
                            ->icon('heroicon-o-calculator'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('frequency')
                            ->label(__('Accrual Frequency'))
                            ->icon('heroicon-o-arrow-path-rounded-square'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('start_type')
                            ->label(__('Start Type'))
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('Created At'))
                            ->icon('heroicon-o-calendar'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('Updated At'))
                            ->icon('heroicon-o-calendar'),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function ($data) {
                        $data['creator_id'] = Auth::user()?->id;
                        $data['sort'] = LeaveAccrualLevel::max('sort') + 1;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
