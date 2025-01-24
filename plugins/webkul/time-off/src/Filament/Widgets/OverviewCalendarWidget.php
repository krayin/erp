<?php

namespace Webkul\TimeOff\Filament\Widgets;

use Carbon\Carbon;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Webkul\TimeOff\Models\Leave;
use Filament\Actions\Action;
use Filament\Forms\Get;
use Filament\Infolists;
use Illuminate\Support\Facades\Auth;
use Webkul\TimeOff\Enums\RequestDateFromPeriod;
use Webkul\TimeOff\Enums\State;

class OverviewCalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = Leave::class;

    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->action(function ($data, $record) {
                    $user = Auth::user();
                    $employee = $user->employee;

                    if ($employee) {
                        $data['employee_id'] = $employee->id;
                    }

                    if ($employee->department) {
                        $data['department_id'] = $employee->department->id;
                    } else {
                        $data['department_id'] = null;
                    }

                    if ($employee->calendar) {
                        $data['calendar_id'] = $employee->calendar->id;
                        $data['number_of_hours'] = $employee->calendar->hours_per_day;
                    }

                    if ($user) {
                        $data['user_id'] = $user->id;

                        $data['company_id'] = $user->default_company_id;

                        $data['employee_company_id'] = $user->default_company_id;
                    }

                    if ($data['request_unit_half']) {
                        $data['duration_display'] = '0.5 day';

                        $data['number_of_days'] = 0.5;
                    } else {
                        $startDate = Carbon::parse($data['request_date_from']);
                        $endDate = $data['request_date_to'] ? Carbon::parse($data['request_date_to']) : $startDate;

                        $data['duration_display'] = $startDate->diffInDays($endDate) + 1 . ' day(s)';

                        $data['number_of_days'] = $startDate->diffInDays($endDate) + 1;
                    }

                    $data['creator_id'] = Auth::user()->id;

                    $data['state'] = State::CONFIRM->value;

                    $data['date_from'] = $data['request_date_from'] ?? null;
                    $data['date_to'] = $data['request_date_to'] ?? null;

                    $record->update($data);
                })
                ->mountUsing(
                    function (Forms\Form $form, array $arguments, $livewire, $data) {
                        $leave = $livewire->record;

                        $newData = [
                            ...$leave->toArray(),
                            'request_date_from' => $arguments['event']['start'] ?? $leave->request_date_from,
                            'request_date_to' => $arguments['event']['end'] ?? $leave->request_date_to,
                        ];

                        $form->fill($newData);
                    }
                ),
            Actions\DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return Actions\ViewAction::make()
            ->modalIcon('heroicon-o-lifebuoy')
            ->modalDescription('View Time Off Request')
            ->infolist($this->infolist());
    }

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->action(function ($data) {
                    $user = Auth::user();
                    $employee = $user->employee;

                    if ($employee) {
                        $data['employee_id'] = $employee->id;
                    }

                    if ($employee->department) {
                        $data['department_id'] = $employee->department->id;
                    } else {
                        $data['department_id'] = null;
                    }

                    if ($employee->calendar) {
                        $data['calendar_id'] = $employee->calendar->id;
                        $data['number_of_hours'] = $employee->calendar->hours_per_day;
                    }

                    if ($user) {
                        $data['user_id'] = $user->id;

                        $data['company_id'] = $user->default_company_id;

                        $data['employee_company_id'] = $user->default_company_id;
                    }

                    if ($data['request_unit_half']) {
                        $data['duration_display'] = '0.5 day';

                        $data['number_of_days'] = 0.5;
                    } else {
                        $startDate = Carbon::parse($data['request_date_from']);
                        $endDate = $data['request_date_to'] ? Carbon::parse($data['request_date_to']) : $startDate;

                        $data['duration_display'] = $startDate->diffInDays($endDate) + 1 . ' day(s)';

                        $data['number_of_days'] = $startDate->diffInDays($endDate) + 1;
                    }

                    $data['creator_id'] = Auth::user()->id;

                    $data['state'] = State::CONFIRM->value;

                    $data['date_from'] = $data['request_date_from'];
                    $data['date_to'] = $data['request_date_to'];

                    Leave::create($data);
                })
                ->mountUsing(
                    function (Forms\Form $form, array $arguments) {
                        $form->fill($arguments);
                    }
                ),
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('holiday_status_id')
                ->label('Time Off Type')
                ->relationship('holidayStatus', 'name')
                ->searchable()
                ->preload()
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

                    return $startDate->diffInDays($endDate) . ' day(s)';
                }),
            Forms\Components\Textarea::make('private_name')
                ->label('Description'),
        ];
    }

    public function infolist(): array
    {
        return [
            Infolists\Components\TextEntry::make('holidayStatus.name')
                ->label('Time Off Type')
                ->icon('heroicon-o-clock'),
            Infolists\Components\TextEntry::make('request_date_from')
                ->label('Start Date')
                ->date()
                ->icon('heroicon-o-calendar-days'),
            Infolists\Components\TextEntry::make('request_date_to')
                ->label('End Date')
                ->date()
                ->icon('heroicon-o-calendar-days'),
            Infolists\Components\TextEntry::make('number_of_days')
                ->label('Duration')
                ->formatStateUsing(fn($state) => $state . ' day(s)')
                ->icon('heroicon-o-clock'),
            Infolists\Components\TextEntry::make('private_name')
                ->label('Description')
                ->icon('heroicon-o-document-text')
                ->placeholder('No description provided'),
            Infolists\Components\TextEntry::make('state')
                ->label('Status')
                ->badge()
                ->formatStateUsing(fn($state) => State::options()[$state])
                ->icon('heroicon-o-check-circle')
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $user = Auth::user();

        return Leave::query()
            ->where('request_date_from', '>=', $fetchInfo['start'])
            ->where('request_date_to', '<=', $fetchInfo['end'])
            ->with('holidayStatus')
            ->get()
            ->map(function (Leave $leave) {
                return [
                    'id'              => $leave->id,
                    'title'           => $leave->holidayStatus?->name,
                    'start'           => $leave->request_date_from,
                    'end'             => $leave->request_date_to,
                    'allDay'          => true,
                    'backgroundColor' => $leave->holidayStatus?->color,
                    'borderColor'     => $leave->holidayStatus?->color,
                    'textColor'       => '#ffffff',
                ];
            })
            ->all();
    }

    public function onDateSelect(string $start, ?string $end, bool $allDay, ?array $view, ?array $resource): void
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : $startDate;

        $this->mountAction('create', [
            'request_date_from' => $startDate->toDateString(),
            'request_date_to'   => $endDate->toDateString(),
        ]);
    }
}
