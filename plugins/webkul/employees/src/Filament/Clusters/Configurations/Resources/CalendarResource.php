<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
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

    public static function getModelLabel(): string
    {
        return __('employees::filament/clusters/configurations/resources/calendar.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('employees::filament/clusters/configurations/resources/calendar.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('employees::filament/clusters/configurations/resources/calendar.navigation.title');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'timezone', 'two_weeks_calendar', 'flexible_hours', 'full_time_required_hours', 'company.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('employees::filament/clusters/configurations/resources/calendar.global-search.name')                     => $record->name ?? '—',
            __('employees::filament/clusters/configurations/resources/calendar.global-search.timezone')                 => $record->timezone ?? '—',
            __('employees::filament/clusters/configurations/resources/calendar.global-search.two-weeks-calendar')       => $record->two_weeks_calendar ?? '—',
            __('employees::filament/clusters/configurations/resources/calendar.global-search.flexible-hours')           => $record->flexible_hours ?? '—',
            __('employees::filament/clusters/configurations/resources/calendar.global-search.full-time-required-hours') => $record->full_time_required_hours ?? '—',
            __('employees::filament/clusters/configurations/resources/calendar.global-search.company-name')             => $record->company?->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar.form.sections.general.title'))
                                    ->schema([
                                        Forms\Components\Hidden::make('creator_id')
                                            ->default(Auth::user()->id),
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.general.fields.schedule-name'))
                                            ->maxLength(255)
                                            ->required()
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('employees::filament/clusters/configurations/resources/calendar.form.sections.general.fields.schedule-name-tooltip')),
                                        Forms\Components\Select::make('timezone')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.general.fields.timezone'))
                                            ->options(function () {
                                                return collect(timezone_identifiers_list())->mapWithKeys(function ($timezone) {
                                                    return [$timezone => $timezone];
                                                });
                                            })
                                            ->default(date_default_timezone_get())
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('employees::filament/clusters/configurations/resources/calendar.form.sections.general.fields.timezone-tooltip')),
                                        Forms\Components\Select::make('company_id')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.general.fields.company'))
                                            ->relationship('company', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ])->columns(2),
                                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar.form.sections.configuration.title'))
                                    ->schema([
                                        Forms\Components\TextInput::make('hours_per_day')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.configuration.fields.hours-per-day'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(24)
                                            ->default(8)
                                            ->suffix(__('employees::filament/clusters/configurations/resources/calendar.form.sections.configuration.fields.hours-per-day-suffix')),
                                        Forms\Components\TextInput::make('full_time_required_hours')
                                            ->label('Full-Time Required Hours')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.configuration.fields.full-time-required-hours'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(168)
                                            ->default(40)
                                            ->suffix(__('employees::filament/clusters/configurations/resources/calendar.form.sections.configuration.fields.full-time-required-hours-suffix')),
                                    ])->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar.form.sections.flexibility.title'))
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.flexibility.fields.status'))
                                            ->default(true)
                                            ->inline(false),
                                        Forms\Components\Toggle::make('two_weeks_calendar')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.flexibility.fields.two-weeks-calendar'))
                                            ->inline(false)
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Enable alternating two-week work schedule'),
                                        Forms\Components\Toggle::make('flexible_hours')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.form.sections.flexibility.fields.flexible-hours'))
                                            ->inline(false)
                                            ->live()
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('employees::filament/clusters/configurations/resources/calendar.form.sections.flexibility.fields.flexible-hours-tooltip')),
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
                Tables\Columns\TextColumn::make('id')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Schedule Name')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('timezone')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.timezone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('flexible_hours')
                    ->sortable()
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.flexible-hours'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.status'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('hours_per_day')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.daily-hours'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.created-by'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('timezone')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.timezone'))
                    ->collapsible(),
                Tables\Grouping\Group::make('flexible_hours')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.flexible-hours'))
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.status'))
                    ->collapsible(),
                Tables\Grouping\Group::make('hours_per_day')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.daily-hours'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->filtersFormColumns(2)
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.company')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.is-active')),
                Tables\Filters\TernaryFilter::make('two_weeks_calendar')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.two-week-calendar')),
                Tables\Filters\TernaryFilter::make('flexible_hours')
                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.flexible-hours')),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.name'))
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('hours_per_day')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.daily-hours'))
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('full_time_required_hours')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.full-time-required-hours'))
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('timezone')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.timezone'))
                            ->icon('heroicon-o-clock'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('attendance')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.attendance'))
                            ->icon('heroicon-o-building-office')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.attendance'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.name'))
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
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.created-by'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('employees::filament/clusters/configurations/resources/calendar.table.filters.updated-at')),
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar.table.actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar.table.actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar.table.actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar.table.actions.force-delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar.table.actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar.table.bulk-actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar.table.bulk-actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/calendar.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/calendar.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.general.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->icon('heroicon-o-clock')
                                            ->placeholder('—')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.general.entries.name')),
                                        Infolists\Components\TextEntry::make('timezone')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-clock')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.general.entries.timezone')),
                                        Infolists\Components\TextEntry::make('company.name')
                                            ->icon('heroicon-o-building-office-2')
                                            ->placeholder('—')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.general.entries.company')),
                                    ])->columns(2),
                                Infolists\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.configuration.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('hours_per_day')
                                            ->placeholder('—')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.configuration.entries.hours-per-day'))
                                            ->icon('heroicon-o-clock')
                                            ->date(),
                                        Infolists\Components\TextEntry::make('full_time_required_hours')
                                            ->placeholder('—')
                                            ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.configuration.entries.full-time-required-hours'))
                                            ->icon('heroicon-o-clock')
                                            ->date(),
                                    ])->columns(2),
                            ])->columnSpan(2),
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.flexibility.title'))
                                ->schema([
                                    Infolists\Components\IconEntry::make('is_active')
                                        ->boolean()
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.flexibility.entries.status')),
                                    Infolists\Components\IconEntry::make('two_weeks_calendar')
                                        ->boolean()
                                        ->placeholder('—')
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.flexibility.entries.two-weeks-calendar')),
                                    Infolists\Components\IconEntry::make('flexible_hours')
                                        ->placeholder('—')
                                        ->boolean()
                                        ->label(__('employees::filament/clusters/configurations/resources/calendar.infolist.sections.flexibility.entries.flexible-hours')),
                                ]),
                        ])->columnSpan(1),
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
