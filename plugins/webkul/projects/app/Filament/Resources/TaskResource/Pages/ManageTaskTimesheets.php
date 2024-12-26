<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Resources\TaskResource;

class ManageTaskTimesheets extends ManageRelatedRecords
{
    protected static string $resource = TaskResource::class;

    protected static string $relationship = 'timesheets';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function getNavigationLabel(): string
    {
        return 'Timesheets';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('type')
                    ->default('projects'),
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('user_id')
                    ->label('Employee')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Description'),
                Forms\Components\TextInput::make('unit_amount')
                    ->label('Time Spent')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->helperText('Time spent in hours (Eg. 1.5 hours means 1 hour 30 minutes)'),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Employee'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('unit_amount')
                    ->label('Time Spent')
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state);
                        $minutes = ($hours - $hours) * 60;

                        return $hours.':'.$minutes;
                    })
                    ->summarize([
                        Sum::make()
                            ->label('Time Spent')
                            ->formatStateUsing(function ($state) {
                                $hours = floor($state);
                                $minutes = ($state - $hours) * 60;

                                return $hours.':'.$minutes;
                            }),
                        Sum::make()
                            ->label('Time Spent on Subtasks')
                            ->formatStateUsing(function ($state) {
                                $subtaskHours = $this->getOwnerRecord()->subtask_effective_hours;
                                $hours = floor($subtaskHours);
                                $minutes = ($subtaskHours - $hours) * 60;

                                return $hours.':'.$minutes;
                            }),
                        Sum::make()
                            ->label('Total Time Spent')
                            ->formatStateUsing(function ($state) {
                                $subtaskHours = $this->getOwnerRecord()->total_hours_spent;
                                $hours = floor($subtaskHours);
                                $minutes = ($subtaskHours - $hours) * 60;

                                return $hours.':'.$minutes;
                            }),
                        Sum::make()
                            ->label('Remaining Time')
                            ->formatStateUsing(function () {
                                $remainingHours = $this->getOwnerRecord()->remaining_hours;

                                $hours = floor($remainingHours);
                                $minutes = ($remainingHours - $hours) * 60;

                                return $hours.':'.$minutes;
                            })
                            ->visible((bool) $this->getOwnerRecord()->allocated_hours),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Timesheet')
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        $ownerRecord = $this->getOwnerRecord();

                        $data['project_id'] = $ownerRecord->project_id;

                        $data['partner_id'] = $ownerRecord->partner_id ?? $ownerRecord->project?->partner_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Timesheet created')
                            ->body('The timesheet has been created successfully.'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Timesheet updated')
                            ->body('The timesheet has been updated successfully.'),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Timesheet deleted')
                            ->body('The timesheet has been deleted successfully.'),
                    ),
            ])
            ->paginated(false);
    }
}
