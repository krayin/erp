<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources;

use Webkul\TimeOff\Filament\Clusters\Management;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\RelationManagers;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Webkul\Employee\Models\Employee;
use Webkul\TimeOff\Models\Leave;
use Webkul\TimeOff\Models\LeaveType;

class TimeOffResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Management::class;

    protected static ?string $modelLabel = 'Time Off';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('employee_id')
                                    ->relationship('employee', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        if ($state) {
                                            $employee = Employee::find($state);

                                            if ($employee->department) {
                                                $set('department_id', $employee->department->id);
                                            } else {
                                                $set('department_id', null);
                                            }
                                        }
                                    })
                                    ->required(),
                                Forms\Components\Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
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
                                            ->options([
                                                'morning'   => 'Morning',
                                                'afternoon' => 'Afternoon',
                                            ])
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
                                        $startDate = Carbon::parse($get('request_date_from'));
                                        $endDate = $get('request_date_to') ? Carbon::parse($get('request_date_to')) : $startDate;

                                        return $startDate->diffInDays($endDate) + 1 . ' day(s)';
                                    }),
                                Forms\Components\TextInput::make('private_name')
                                    ->label('Private Name')
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
                                    ->live()
                            ])
                    ])->columns(2)
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
            'index'  => Pages\ListTimeOffs::route('/'),
            'create' => Pages\CreateTimeOff::route('/create'),
            'edit'   => Pages\EditTimeOff::route('/{record}/edit'),
        ];
    }
}
