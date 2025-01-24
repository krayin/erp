<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use Webkul\TimeOff\Enums\AccruedGainTime;
use Webkul\TimeOff\Enums\CarryoverDate;
use Webkul\TimeOff\Enums\CarryoverDay;
use Webkul\TimeOff\Enums\CarryoverMonth;
use Webkul\TimeOff\Filament\Clusters\Configurations;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\Pages;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\RelationManagers;
use Webkul\TimeOff\Models\LeaveAccrualPlan;

class AccrualPlanResource extends Resource
{
    protected static ?string $model = LeaveAccrualPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Configurations::class;

    protected static ?int $navigationSort = 2;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        if (str_contains(Route::currentRouteName(), 'index')) {
            return SubNavigationPosition::Start;
        }

        return SubNavigationPosition::Top;
    }

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
                                Forms\Components\Radio::make('accrued_gain_time')
                                    ->label(__('Accrued Gain Time'))
                                    ->options(AccruedGainTime::class)
                                    ->default(AccruedGainTime::END->value)
                                    ->required(),
                                Forms\Components\Radio::make('carryover_date')
                                    ->label(__('Carry-Over Time'))
                                    ->options(CarryoverDate::class)
                                    ->default(CarryoverDate::OTHER->value)
                                    ->live()
                                    ->required(),
                                Forms\Components\Fieldset::make()
                                    ->label('Carry-Over Date')
                                    ->live()
                                    ->visible(function (Get $get) {
                                        return $get('carryover_date') === CarryoverDate::OTHER->value;
                                    })
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
                                    ])->columns(2),
                                Forms\Components\Toggle::make('is_active')
                                    ->inline(false)
                                    ->label(__('Status')),
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('leaveAccrualLevels')
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->leaveAccrualLevels?->count())
                    ->label(__('Levels')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 2])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make(__('Basic Information'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->icon('heroicon-o-user')
                                            ->placeholder('—')
                                            ->label(__('Name')),
                                        Infolists\Components\IconEntry::make('is_based_on_worked_time')
                                            ->boolean()
                                            ->label(__('Is Based On Worked Time')),
                                        Infolists\Components\TextEntry::make('accrued_gain_time')
                                            ->icon('heroicon-o-clock')
                                            ->placeholder('—')
                                            ->formatStateUsing(fn($state) => AccruedGainTime::options()[$state])
                                            ->label(__('Accrued Gain Time')),
                                        Infolists\Components\TextEntry::make('carryover_date')
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('—')
                                            ->formatStateUsing(fn($state) => CarryoverDate::options()[$state])
                                            ->label(__('Carryover Time')),
                                        Infolists\Components\TextEntry::make('carryover_day')
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('—')
                                            ->formatStateUsing(fn($state) => CarryoverDay::options()[$state])
                                            ->label(__('Carryover Day')),
                                        Infolists\Components\TextEntry::make('carryover_month')
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('—')
                                            ->formatStateUsing(fn($state) => CarryoverMonth::options()[$state])
                                            ->label(__('Carryover Month')),
                                    ]),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewAccrualPlan::class,
            Pages\EditAccrualPlan::class,
            Pages\ManageMilestone::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Manage Milestones', [
                RelationManagers\MilestoneRelationManager::class,
            ])
                ->icon('heroicon-o-clipboard-list'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListAccrualPlans::route('/'),
            'create'     => Pages\CreateAccrualPlan::route('/create'),
            'view'       => Pages\ViewAccrualPlan::route('/{record}'),
            'edit'       => Pages\EditAccrualPlan::route('/{record}/edit'),
            'milestones' => Pages\ManageMilestone::route('/{record}/milestones'),
            // 'skills' => Pages\ManageSkill::route('/{record}/skills'),
        ];
    }
}
