<?php

namespace Webkul\TimeOff\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms;
use Carbon\Carbon;

class CalendarWidget extends FullCalendarWidget
{
    public function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('time_off_type')
                ->label('Time Off Type')
                ->options([
                    'paid' => 'Paid Time Off',
                    'unpaid' => 'Unpaid Time Off',
                    'sick' => 'Sick Leave',
                    'other' => 'Other',
                ])
                ->required()
                ->default('paid'),

            Forms\Components\TextInput::make('name')
                ->label('Description')
                ->required()
                ->maxLength(255),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DatePicker::make('starts_at')
                        ->label('Start Date')
                        ->required()
                        ->native(false)
                        ->closeOnDateSelection(),

                    Forms\Components\DatePicker::make('ends_at')
                        ->label('End Date')
                        ->required()
                        ->native(false)
                        ->closeOnDateSelection(),
                ]),

            Forms\Components\Select::make('duration_type')
                ->label('Duration')
                ->options([
                    'full_day' => 'Full Day',
                    'half_day_morning' => 'Half Day - Morning',
                    'half_day_afternoon' => 'Half Day - Afternoon',
                    'custom_hours' => 'Custom Hours',
                ])
                ->required()
                ->default('full_day')
                ->reactive(),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\TimePicker::make('custom_start_time')
                        ->label('Start Time')
                        ->native(false)
                        ->visible(fn(callable $get) => $get('duration_type') === 'custom_hours'),

                    Forms\Components\TimePicker::make('custom_end_time')
                        ->label('End Time')
                        ->native(false)
                        ->visible(fn(callable $get) => $get('duration_type') === 'custom_hours'),
                ]),

            Forms\Components\Textarea::make('reason')
                ->label('Reason')
                ->rows(3),
        ];
    }

    public function fetchEvents(array $info): array
    {

        $today = Carbon::now();

        return [
            [
                'id' => 1,
                'title' => 'Vacation in Paris',
                'start' => $today->subDays(10)->toDateString(),
                'end' => $today->subDays(5)->toDateString(),
                'allDay' => true,
                'backgroundColor' => $this->getEventColor('paid'),
                'borderColor' => $this->getEventColor('paid'),
                'textColor' => '#ffffff',
            ],
            [
                'id' => 2,
                'title' => 'Sick Leave',
                'start' => $today->toDateString(),
                'end' => $today->addDay()->toDateString(),
                'allDay' => true,
                'backgroundColor' => $this->getEventColor('sick'),
                'borderColor' => $this->getEventColor('sick'),
                'textColor' => '#ffffff',
            ],
            [
                'id' => 3,
                'title' => 'Family Wedding',
                'start' => $today->addDays(5)->toDateString(),
                'end' => $today->addDays(7)->toDateString(),
                'allDay' => true,
                'backgroundColor' => $this->getEventColor('paid'),
                'borderColor' => $this->getEventColor('paid'),
                'textColor' => '#ffffff',
            ],
        ];
    }

    public function onDateSelect(string $start, ?string $end, bool $allDay, ?array $view, ?array $resource): void
    {
        $data = [
            'starts_at' => Carbon::parse($start)->toDateString(),
            'ends_at' => $end ? Carbon::parse($end)->toDateString() : $start,
            'duration_type' => 'full_day',
            'name' => Carbon::parse($start)->format('M d') .
                ($start !== $end
                    ? ' - ' . Carbon::parse($end)->format('M d')
                    : '') .
                ' Time Off',
        ];

        $this->mountAction('create', [
            'type' => 'select',
            'data' => $data,
        ]);
    }

    protected function getEventColor(string $type): string
    {
        return match ($type) {
            'paid' => '#28a745',
            'unpaid' => '#dc3545',
            'sick' => '#ffc107',
            default => '#6c757d',
        };
    }
}
