<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources;

use Webkul\TimeOff\Filament\Clusters\Configurations;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\TimeOff\Enums\AccruedGainTime;
use Webkul\TimeOff\Enums\CarryoverDate;
use Webkul\TimeOff\Enums\CarryoverDay;
use Webkul\TimeOff\Enums\CarryoverMonth;
use Webkul\TimeOff\Models\LeaveAccrualPlan;

class AccrualPlanResource extends Resource
{
    protected static ?string $model = LeaveAccrualPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return __('Accrual Plan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Name'))
                                    ->required()
                                    ->placeholder(__('Name')),

                                Forms\Components\Toggle::make('is_based_on_worked_time')
                                    ->inline(false)
                                    ->label(__('Is Based On Worked Time')),
                                Forms\Components\Radio::make('accrual_type')
                                    ->label(__('Accrued Gain Time'))
                                    ->options(AccruedGainTime::class)
                                    ->default(AccruedGainTime::END->value)
                                    ->required(),
                                Forms\Components\Radio::make('carryover_date')
                                    ->label(__('Carry-Over Time'))
                                    ->options(CarryoverDate::class)
                                    ->default(CarryoverDate::OTHER->value)
                                    ->required(),
                                Forms\Components\Fieldset::make()
                                    ->label('Carry-Over Date')
                                    ->schema([
                                        Forms\Components\Select::make('carryover_day')
                                            ->hiddenLabel()
                                            ->options(CarryoverDay::class)
                                            ->maxWidth(MaxWidth::ExtraSmall)
                                            ->default(CarryoverDay::DAY_1->value)
                                            ->required(),
                                        Forms\Components\Select::make('carryover_month')
                                            ->hiddenLabel()
                                            ->options(CarryoverMonth::class)
                                            ->default(CarryoverMonth::JAN->value)
                                            ->required(),
                                    ])->columns(2)
                            ]),
                    ])->columns(2),
            ]);
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListAccrualPlans::route('/'),
            'create' => Pages\CreateAccrualPlan::route('/create'),
            'view' => Pages\ViewAccrualPlan::route('/{record}'),
            'edit' => Pages\EditAccrualPlan::route('/{record}/edit'),
        ];
    }
}
