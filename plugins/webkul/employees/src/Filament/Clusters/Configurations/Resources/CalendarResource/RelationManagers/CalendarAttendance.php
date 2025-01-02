<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums;
use Webkul\Employee\Enums\DayOfWeek;

class CalendarAttendance extends RelationManager
{
    protected static string $relationship = 'attendance';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.modal.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.general.title'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.general.fields.attendance-name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('day_of_week')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.general.fields.day-of-week'))
                            ->searchable()
                            ->preload()
                            ->options(Enums\DayOfWeek::options())
                            ->required(),
                    ]),
                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.timing-information.title'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('day_period')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.timing-information.fields.day-period'))
                            ->searchable()
                            ->preload()
                            ->options(Enums\DayPeriod::options())
                            ->required(),
                        Forms\Components\Select::make('week_type')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.timing-information.fields.week-type'))
                            ->searchable()
                            ->preload()
                            ->options(Enums\WeekType::options()),
                        Forms\Components\TimePicker::make('hour_from')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.timing-information.fields.work-from'))
                            ->native(false)
                            ->required(),
                        Forms\Components\TimePicker::make('hour_to')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.timing-information.fields.work-to'))
                            ->native(false)
                            ->required(),
                    ]),
                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.date-information.title'))
                    ->columns(2)
                    ->schema([
                        Forms\Components\DatePicker::make('date_from')
                            ->native(false)
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.date-information.fields.starting-date')),
                        Forms\Components\DatePicker::make('date_to')
                            ->native(false)
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.date-information.fields.ending-date')),
                    ]),
                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.additional-information.title'))
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('display_type')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.additional-information.fields.display-type'))
                            ->options(Enums\CalendarDisplayType::options()),
                        Forms\Components\TextInput::make('duration_days')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.form.sections.additional-information.fields.durations-days'))
                            ->numeric()
                            ->default(1),
                        Forms\Components\Hidden::make('creator_id')
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
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.day-of-week'))
                    ->formatStateUsing(fn ($state) => DayOfWeek::options()[$state]),
                Tables\Columns\TextColumn::make('day_period')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.day-period'))
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('hour_from')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.work-from')),
                Tables\Columns\TextColumn::make('hour_to')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.work-to')),
                Tables\Columns\TextColumn::make('date_from')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.starting-date'))
                    ->date(),
                Tables\Columns\TextColumn::make('date_to')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.ending-date'))
                    ->date(),
                Tables\Columns\TextColumn::make('display_type')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.display-type'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.created-at'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.columns.updated-at'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.filters.day-of-week'))
                    ->options(Enums\DayOfWeek::options()),
                Tables\Filters\SelectFilter::make('display_type')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.filters.display-type'))
                    ->searchable()
                    ->preload()
                    ->options(Enums\CalendarDisplayType::options()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.create.notification.title'))
                            ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.create.notification.body')),
                    )
                    ->icon('heroicon-o-plus-circle')
                    ->hidden(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->flexible_hours ?? false)
                    ->mutateFormDataUsing(function (array $data) {
                        $data['sort'] = $this->getOwnerRecord()->attendance()->count() + 1;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.edit.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.edit.notification.body')),
                        )
                        ->mutateFormDataUsing(function (array $data) {
                            $data['sort'] = $this->getOwnerRecord()->attendance()->count() + 1;

                            return $data;
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.actions.restore.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.bulk-actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.bulk-actions.force-delete.notification.body')),
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.bulk-actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.table.bulk-actions.restore.notification.body')),
                        ),
                ]),
            ])
            ->reorderable('sort');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.general.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-clock')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.general.entries.name')),
                                        Infolists\Components\TextEntry::make('day_of_week')
                                            ->formatStateUsing(fn ($state) => Enums\DayOfWeek::options()[$state])
                                            ->placeholder('—')
                                            ->icon('heroicon-o-clock')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.general.entries.day-of-week')),
                                    ])->columns(2),
                                Infolists\Components\Section::make((__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.timing-information.title')))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('day_period')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.timing-information.entries.day-period'))
                                            ->placeholder('—')
                                            ->formatStateUsing(fn ($state) => Enums\DayPeriod::options()[$state])
                                            ->icon('heroicon-o-clock'),
                                        Infolists\Components\TextEntry::make('week_type')
                                            ->formatStateUsing(fn ($state) => Enums\WeekType::options()[$state])
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.timing-information.entries.week-type'))
                                            ->placeholder('—')
                                            ->icon('heroicon-o-clock'),
                                        Infolists\Components\TextEntry::make('hour_from')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.timing-information.entries.work-from'))
                                            ->placeholder('—')
                                            ->icon('heroicon-o-clock'),
                                        Infolists\Components\TextEntry::make('hour_to')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.timing-information.entries.work-to'))
                                            ->placeholder('—')
                                            ->icon('heroicon-o-clock'),
                                    ])->columns(2),

                            ])->columnSpan(2),
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make((__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.date-information.title')))
                                ->schema([
                                    Infolists\Components\TextEntry::make('date_from')
                                        ->icon('heroicon-o-calendar')
                                        ->placeholder('—')
                                        ->date()
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.date-information.entries.starting-date')),
                                    Infolists\Components\TextEntry::make('date_to')
                                        ->icon('heroicon-o-calendar')
                                        ->placeholder('—')
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.date-information.entries.ending-date'))
                                        ->date(),
                                ]),
                            Infolists\Components\Section::make((__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.additional-information.title')))
                                ->schema([
                                    Infolists\Components\TextEntry::make('display_type')
                                        ->formatStateUsing(fn ($state) => Enums\CalendarDisplayType::options()[$state])
                                        ->placeholder('—')
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.additional-information.entries.display-type'))
                                        ->icon('heroicon-o-clock'),
                                    Infolists\Components\TextEntry::make('duration_days')
                                        ->placeholder('—')
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar/relation-managers/working-hours.infolist.sections.additional-information.entries.durations-days'))
                                        ->icon('heroicon-o-clock'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }
}
