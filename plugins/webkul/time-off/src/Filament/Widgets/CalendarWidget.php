<?php

namespace Webkul\TimeOff\Filament\Widgets;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;
use Webkul\TimeOff\Models\Leave;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = Leave::class;

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->form(fn(Form $form) => TimeOffResource::form($form))
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
                ->content(fn($state): string => $state),
            Forms\Components\Textarea::make('reason')
                ->label('Reason')
                ->placeholder('Write the reason for your time off')
                ->rows(3),
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Leave::query()
            ->where('request_date_from', '>=', $fetchInfo['start'])
            ->where('request_date_to', '<=', $fetchInfo['end'])
            ->with('holidayStatus')
            ->get()
            ->map(function (Leave $leave) {
                return [
                    'id' => $leave->id,
                    'title' => $leave->holidayStatus->name,
                    'start' => $leave->request_date_from,
                    'end' => $leave->request_date_to,
                    'allDay' => true,
                    'backgroundColor' => $leave->holidayStatus->color,
                    'borderColor' => $leave->holidayStatus->color,
                    'textColor' => '#ffffff',
                ];
            })
            ->all();
    }

    public function onDateSelect(string $start, ?string $end, bool $allDay, ?array $view, ?array $resource): void
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : $startDate;

        $numberOfDays = $startDate->diffInDays($endDate) + 1;

        $this->mountAction('create', [
            'start_date'     => $startDate->toDateString(),
            'end_date'       => $endDate->toDateString(),
            'requested_days' => $numberOfDays . ' Days',
        ]);
    }
}
