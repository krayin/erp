<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources;

use Webkul\TimeOff\Filament\Clusters\MyTime;
use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\TimeOff\Enums\AllocationType;
use Webkul\TimeOff\Enums\State;
use Webkul\TimeOff\Models\LeaveAllocation;

class MyAllocationResource extends Resource
{
    protected static ?string $model = LeaveAllocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $cluster = MyTime::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'My Allocation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        ProgressStepper::make('state')
                            ->hiddenLabel()
                            ->inline()
                            ->options(function ($record) {
                                $onlyStates = [
                                    State::CONFIRM->value,
                                    State::VALIDATE_TWO->value,
                                ];

                                if ($record) {
                                    if ($record->state === State::REFUSE->value) {
                                        $onlyStates[] = State::REFUSE->value;
                                    }
                                }

                                return collect(State::options())->only($onlyStates)->toArray();
                            })
                            ->default(State::CONFIRM->value)
                            ->columnSpan('full')
                            ->disabled()
                            ->reactive()
                            ->live(),
                    ])->columns(2),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->placeholder('Time Off Type (From validity start to validity end/no limit)')
                                    ->required(),
                                Forms\Components\Grid::make(1)
                                    ->schema([
                                        Forms\Components\Select::make('holiday_status_id')
                                            ->label('Time Off Type')
                                            ->relationship('holidayStatus', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ]),
                                Forms\Components\Radio::make('allocation_type')
                                    ->label('Allocation Type')
                                    ->options(AllocationType::class)
                                    ->default(AllocationType::REGULAR->value)
                                    ->required(),
                                Forms\Components\Fieldset::make('Validity Period')
                                    ->schema([
                                        Forms\Components\DatePicker::make('date_from')
                                            ->label('From')
                                            ->native(false)
                                            ->required()
                                            ->default(now()),
                                        Forms\Components\DatePicker::make('date_to')
                                            ->label('To')
                                            ->native(false)
                                            ->placeholder('No limit'),
                                    ]),
                                Forms\Components\TextInput::make('number_of_days')
                                    ->label('Allocation')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->suffix('Number of Days'),
                                Forms\Components\RichEditor::make('notes')
                                    ->label('Reason'),
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('holidayStatus.name')
                    ->label(__('Time Off Type'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_of_days')
                    ->label(__('Amount'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('allocation_type')
                    ->formatStateUsing(fn($state) => AllocationType::options()[$state])
                    ->label(__('Allocation Type'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn($state) => State::options()[$state])
                    ->label(__('Status'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('employee.name')
                    ->label(__('Employee Name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('holidayStatus.name')
                    ->label(__('Time Off Type'))
                    ->collapsible(),
                Tables\Grouping\Group::make('allocation_type')
                    ->label(__('Allocation Type'))
                    ->collapsible(),
                Tables\Grouping\Group::make('state')
                    ->label(__('Status'))
                    ->collapsible(),
                Tables\Grouping\Group::make('date_from')
                    ->label(__('Start Date'))
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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
                ]),
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
            'index'  => Pages\ListMyAllocations::route('/'),
            'create' => Pages\CreateMyAllocation::route('/create'),
            'edit'   => Pages\EditMyAllocation::route('/{record}/edit'),
            'view'   => Pages\ViewMyAllocation::route('/{record}'),
        ];
    }
}
