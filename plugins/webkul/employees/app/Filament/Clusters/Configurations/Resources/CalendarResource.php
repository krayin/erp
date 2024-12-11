<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                                        Forms\Components\TextInput::make('name')
                                            ->label('Schedule Name')
                                            ->maxLength(255)
                                            ->required()
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('Enter a descriptive name for this work schedule')
                                            ),
                                        Forms\Components\Select::make('tz')
                                            ->label('Time Zone')
                                            ->options(function () {
                                                return collect(timezone_identifiers_list())->mapWithKeys(function ($timezone) {
                                                    return [$timezone => $timezone];
                                                });
                                            })
                                            ->default(date_default_timezone_get())
                                            ->preload()
                                            ->searchable()
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('Specify the time zone for this work schedule')
                                            ),
                                        Forms\Components\Select::make('company_id')
                                            ->label('Company')
                                            ->relationship('company', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\Select::make('user_id')
                                            ->label('Primary Contact')
                                            ->relationship('user', 'name')
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
                                            ->suffix('hours per week'),
                                    ])->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Schedule Flexibility')
                                    ->schema([
                                        Forms\Components\Toggle::make('status')
                                            ->label('Status')
                                            ->default(true)
                                            ->inline(false),
                                        Forms\Components\Toggle::make('two_weeks_calendar')
                                            ->label('Two Weeks Calendar')
                                            ->inline(false)
                                            ->hint('Enable alternating two-week work schedule'),
                                        Forms\Components\Toggle::make('flexible_hours')
                                            ->label('Flexible Hours')
                                            ->inline(false)
                                            ->live()
                                            ->hint('Allow employees to have flexible work hours'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Schedule Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tz')
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
                Tables\Columns\IconColumn::make('status')
                    ->sortable()
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('hours_per_day')
                    ->label('Daily Hours')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('tz')
                    ->label('Time Zone')
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label('Country')
                    ->collapsible(),
                Tables\Grouping\Group::make('flexible_hours')
                    ->label('Flexible Hours')
                    ->collapsible(),
                Tables\Grouping\Group::make('status')
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
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('Company'),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('flexible_hours')
                    ->label('Flexible Hours'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
