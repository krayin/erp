<?php

namespace Webkul\TimeOff\Filament\Widgets;

use Carbon\Carbon;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Webkul\TimeOff\Models\Leave;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = Leave::class;

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
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
            Forms\Components\Select::make('time_off_type')
                ->label('Time Off Type')
                ->relationship('holidayStatus', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date')
                        ->required()
                        ->native(false)
                        ->closeOnDateSelection()
                        ->live(),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date')
                        ->required()
                        ->native(false)
                        ->closeOnDateSelection()
                        ->live(),
                ]),
            Forms\Components\Placeholder::make('requested_days')
                ->label('Requested Days')
                ->content(fn ($state): string => $state),
            Forms\Components\Textarea::make('reason')
                ->label('Reason')
                ->placeholder('Write the reason for your time off')
                ->rows(3),
        ];
    }

    public function fetchEvents(array $info): array
    {
        $today = Carbon::now();

        // $events = Leave::all()->map(function ($leave) {
        //     return [
        //         'id' => $leave->id,
        //         'title' => $leave->holidayStatus->name,
        //         'start' => $leave->request_date_from,
        //         'end' => $leave->request_date_to,
        //         'allDay' => true,
        //         'backgroundColor' => $this->getEventColor('paid'),
        //         'borderColor' => $this->getEventColor('paid'),
        //         'textColor' => '#ffffff',
        //     ];
        // });

        return [
            [
                'id'              => 1,
                'title'           => 'Vacation in Paris',
                'start'           => $today->subDays(10)->toDateString(),
                'end'             => $today->subDays(5)->toDateString(),
                'allDay'          => true,
                'backgroundColor' => $this->getEventColor('paid'),
                'borderColor'     => $this->getEventColor('paid'),
                'textColor'       => '#ffffff',
            ],
            [
                'id'              => 2,
                'title'           => 'Sick Leave',
                'start'           => $today->toDateString(),
                'end'             => $today->addDay()->toDateString(),
                'allDay'          => true,
                'backgroundColor' => $this->getEventColor('sick'),
                'borderColor'     => $this->getEventColor('sick'),
                'textColor'       => '#ffffff',
            ],
            [
                'id'              => 3,
                'title'           => 'Family Wedding',
                'start'           => $today->addDays(5)->toDateString(),
                'end'             => $today->addDays(7)->toDateString(),
                'allDay'          => true,
                'backgroundColor' => $this->getEventColor('paid'),
                'borderColor'     => $this->getEventColor('paid'),
                'textColor'       => '#ffffff',
            ],
        ];
    }

    public function onDateSelect(string $start, ?string $end, bool $allDay, ?array $view, ?array $resource): void
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : $startDate;

        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        $data = [
            'start_date'     => $startDate->toDateString(),
            'end_date'       => $endDate->toDateString(),
            'requested_days' => $numberOfDays.' Days',
        ];

        $this->mountAction('create', $data);
    }

    protected function getEventColor(string $type): string
    {
        return match ($type) {
            'paid'   => '#28a745',
            'unpaid' => '#dc3545',
            'sick'   => '#ffc107',
            default  => '#6c757d',
        };
    }
}
