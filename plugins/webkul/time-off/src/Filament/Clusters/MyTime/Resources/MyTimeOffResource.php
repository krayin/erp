<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources;

use Webkul\TimeOff\Filament\Clusters\MyTime;
use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Webkul\TimeOff\Enums\RequestDateFromPeriod;
use Webkul\TimeOff\Enums\State;
use Webkul\TimeOff\Models\Leave;
use Webkul\TimeOff\Models\LeaveType;

class MyTimeOffResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

    protected static ?string $modelLabel = 'My Time Off';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = MyTime::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('holiday_status_id')
                                    ->relationship('holidayStatus', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required(),
                                Forms\Components\Fieldset::make()
                                    ->label(function (Get $get) {
                                        if ($get('request_unit_half')) {
                                            return 'Date';
                                        } else {
                                            return 'Dates';
                                        }
                                    })
                                    ->live()
                                    ->schema([
                                        Forms\Components\DatePicker::make('request_date_from')
                                            ->native(false)
                                            ->default(now())
                                            ->required(),
                                        Forms\Components\DatePicker::make('request_date_to')
                                            ->native(false)
                                            ->default(now())
                                            ->hidden(fn(Get $get) => $get('request_unit_half'))
                                            ->required(),
                                        Forms\Components\Select::make('request_date_from_period')
                                            ->label('Period')
                                            ->options(RequestDateFromPeriod::class)
                                            ->default(RequestDateFromPeriod::MORNING->value)
                                            ->native(false)
                                            ->visible(fn(Get $get) => $get('request_unit_half'))
                                            ->required(),
                                    ]),
                                Forms\Components\Toggle::make('request_unit_half')
                                    ->live()
                                    ->label('Half Day'),
                                Forms\Components\Placeholder::make('requested_days')
                                    ->label('Requested (Days/Hours)')
                                    ->live()
                                    ->inlineLabel()
                                    ->reactive()
                                    ->content(function ($state, Get $get): string {
                                        if ($get('request_unit_half')) {
                                            return '0.5 day';
                                        }

                                        $startDate = Carbon::parse($get('request_date_from'));
                                        $endDate = $get('request_date_to') ? Carbon::parse($get('request_date_to')) : $startDate;

                                        return $startDate->diffInDays($endDate) + 1 . ' day(s)';
                                    }),
                                Forms\Components\Textarea::make('private_name')
                                    ->label('Description')
                                    ->live(),
                                Forms\Components\FileUpload::make('attachment')
                                    ->label('Attachment')
                                    ->visible(function (Get $get) {
                                        $leaveType = LeaveType::find($get('holiday_status_id'));

                                        if ($leaveType) {
                                            return $leaveType->support_document;
                                        }

                                        return false;
                                    })
                                    ->live(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->label(__('Employee Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('holidayStatus.name')
                    ->label(__('Time Off Type'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('private_name')
                    ->label(__('Description'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_from')
                    ->label(__('Date From'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_to')
                    ->label(__('Date To'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration_display')
                    ->label(__('Duration'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('Status'))
                    ->formatStateUsing(fn($state) => State::options()[$state])
                    ->sortable()
                    ->badge()
                    ->searchable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('employee.name')
                    ->label(__('Employee Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('holidayStatus.name')
                    ->label(__('Time Off Type'))
                    ->collapsible(),
                Tables\Grouping\Group::make('state')
                    ->label(__('Status'))
                    ->collapsible(),
                Tables\Grouping\Group::make('date_from')
                    ->label(__('Start Date'))
                    ->collapsible(),
                Tables\Grouping\Group::make('date_to')
                    ->label(__('Start To'))
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn($record) => $record->state === State::VALIDATE_TWO->value)
                    ->action(function ($record) {
                        if ($record->state === State::VALIDATE_ONE->value) {
                            $record->update(['state' => State::VALIDATE_TWO->value]);
                        } else {
                            $record->update(['state' => State::VALIDATE_TWO->value]);
                        }
                    })
                    ->label(function ($record) {
                        if ($record->state === State::VALIDATE_ONE->value) {
                            return 'Validate';
                        } else {
                            return 'Approve';
                        }
                    }),
                Tables\Actions\Action::make('refuse')
                    ->icon('heroicon-o-x-circle')
                    ->hidden(fn($record) => $record->state === State::REFUSE->value)
                    ->color('danger')
                    ->action(function ($record) {
                        $record->update(['state' => State::REFUSE->value]);
                    })
                    ->label('Refuse'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->where('employee_id', Auth::user()->employee->id);
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMyTimeOffs::route('/'),
            'create' => Pages\CreateMyTimeOff::route('/create'),
            'edit'   => Pages\EditMyTimeOff::route('/{record}/edit'),
            'view'   => Pages\ViewMyTimeOff::route('/{record}'),
        ];
    }
}
