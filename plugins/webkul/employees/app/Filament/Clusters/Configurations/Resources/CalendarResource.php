<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\RelationManagers;
use Webkul\Employee\Models\Calendar;

class CalendarResource extends Resource
{
    protected static ?string $model = Calendar::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $cluster = Configurations::class;

    protected static ?string $navigationGroup = 'Employee';

    public static function getModelLabel(): string
    {
        return 'Working Schedules';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Work Schedule Details')
                                    ->schema([
                                        Forms\Components\Hidden::make('creator_id')
                                            ->default(Auth::user()->id),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Schedule Name')
                                            ->maxLength(255)
                                            ->required()
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Enter a descriptive name for this work schedule'),
                                        Forms\Components\Select::make('timezone')
                                            ->label('Time Zone')
                                            ->options(function () {
                                                return collect(timezone_identifiers_list())->mapWithKeys(function ($timezone) {
                                                    return [$timezone => $timezone];
                                                });
                                            })
                                            ->default(date_default_timezone_get())
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Specify the time zone for this work schedule'),
                                        Forms\Components\Select::make('company_id')
                                            ->label('Company')
                                            ->relationship('company', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Work Hours Configuration')
                                    ->schema([
                                        Forms\Components\TextInput::make('hours_per_day')
                                            ->label('Hours per Day')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(24)
                                            ->default(8)
                                            ->suffix('hours'),
                                        Forms\Components\TextInput::make('full_time_required_hours')
                                            ->label('Full-Time Required Hours')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(168)
                                            ->default(40)
                                            ->suffix('Hours per week'),
                                    ])->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Schedule Flexibility')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Status')
                                            ->default(true)
                                            ->inline(false),
                                        Forms\Components\Toggle::make('two_weeks_calendar')
                                            ->label('Two Weeks Calendar')
                                            ->inline(false)
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Enable alternating two-week work schedule'),
                                        Forms\Components\Toggle::make('flexible_hours')
                                            ->label('Flexible Hours')
                                            ->inline(false)
                                            ->live()
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Allow employees to have flexible work hours'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make('Work Schedule Details')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->icon('heroicon-o-clock')
                                            ->label('Schedule Name'),
                                        Infolists\Components\TextEntry::make('timezone')
                                            ->icon('heroicon-o-clock')
                                            ->label('Time Zone'),
                                        Infolists\Components\TextEntry::make('company.name')
                                            ->icon('heroicon-o-building-office-2')
                                            ->label('Company'),
                                    ])->columns(2),
                                Infolists\Components\Section::make('Work Hours Configuration')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('hours_per_day')
                                            ->label('Hours Per Day')
                                            ->icon('heroicon-o-clock')
                                            ->date(),
                                    ]),
                            ])->columnSpan(2),
                        Infolists\Components\Group::make([

                            Infolists\Components\Section::make('Schedule Flexibility')
                                ->schema([
                                    Infolists\Components\IconEntry::make('is_active')
                                        ->boolean()
                                        ->label('Status'),
                                    Infolists\Components\IconEntry::make('two_weeks_calendar')
                                        ->boolean()
                                        ->label('Two Week Calendar'),
                                    Infolists\Components\IconEntry::make('flexible_hours')
                                        ->boolean()
                                        ->label('Flexible Hours'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Schedule Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timezone')
                    ->label('Time Zone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('flexible_hours')
                    ->sortable()
                    ->label('Flexible Hours')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('hours_per_day')
                    ->label('Daily Hours')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('timezone')
                    ->label('Time Zone')
                    ->collapsible(),
                Tables\Grouping\Group::make('flexible_hours')
                    ->label('Flexible Hours')
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('hours_per_day')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
            ])
            ->filtersFormColumns(2)
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Company'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('two_weeks_calendar')
                    ->label('Two Weeks Calendar'),
                Tables\Filters\TernaryFilter::make('flexible_hours')
                    ->label('Flexible Hours'),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label('Name')
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('hours_per_day')
                            ->label('Hours Per Day')
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('full_time_required_hours')
                            ->label('Full Time Required Hours')
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('timezone')
                            ->label('Timezone')
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('attendance')
                            ->label('Attendance')
                            ->icon('heroicon-o-building-office')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label('Company')
                            ->icon('heroicon-o-building-office')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('createdBy')
                            ->label('Created By')
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CalendarAttendance::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCalendars::route('/'),
            'create' => Pages\CreateCalendar::route('/create'),
            'view'   => Pages\ViewCalendar::route('/{record}'),
            'edit'   => Pages\EditCalendar::route('/{record}/edit'),
        ];
    }
}
