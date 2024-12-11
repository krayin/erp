<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums;

class CalendarAttendance extends RelationManager
{
    protected $listeners = ['refreshCalendarResource' => '$refresh'];

    protected static string $relationship = 'attendance';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Working Hours';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Attendance Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('day_of_week')
                            ->label('Day of Week')
                            ->searchable()
                            ->preload()
                            ->options(Enums\DayOfWeek::options())
                            ->required(),
                    ]),
                Forms\Components\Section::make('Timing Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('day_period')
                            ->label('Day Period')
                            ->searchable()
                            ->preload()
                            ->options(Enums\DayPeriod::options())
                            ->required(),
                        Forms\Components\Select::make('week_type')
                            ->label('Week Type')
                            ->searchable()
                            ->preload()
                            ->options(Enums\WeekType::options()),
                        Forms\Components\TimePicker::make('hour_from')
                            ->label('Work From')
                            ->native(false)
                            ->required(),
                        Forms\Components\TimePicker::make('hour_to')
                            ->label('Work To')
                            ->native(false)
                            ->required(),
                    ]),
                Forms\Components\Section::make('Date Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DatePicker::make('date_from')
                            ->native(false)
                            ->label('Starting Date'),
                        Forms\Components\DatePicker::make('date_to')
                            ->native(false)
                            ->label('Ending Date'),
                    ]),
                Forms\Components\Section::make('Additional Details')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('display_type')
                            ->label('Display Type')
                            ->options(Enums\DisplayType::options()),
                        Forms\Components\TextInput::make('durations_days')
                            ->label('Duration (Days)')
                            ->numeric()
                            ->default(1),
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::user()->id),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Day')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('day_period')
                    ->label('Period')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('hour_from')
                    ->label('Work From'),
                Tables\Columns\TextColumn::make('hour_to')
                    ->label('Work To'),
                Tables\Columns\TextColumn::make('date_from')
                    ->label('Starting Date')
                    ->date(),
                Tables\Columns\TextColumn::make('date_to')
                    ->label('Ending Date')
                    ->date(),
                Tables\Columns\TextColumn::make('display_type')
                    ->label('Type')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label('Day of Week')
                    ->options(Enums\DayOfWeek::options()),
                Tables\Filters\SelectFilter::make('display_type')
                    ->label('Display Type')
                    ->searchable()
                    ->preload()
                    ->options(Enums\DisplayType::options()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->hidden(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->flexible_hours ?? false),
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
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
