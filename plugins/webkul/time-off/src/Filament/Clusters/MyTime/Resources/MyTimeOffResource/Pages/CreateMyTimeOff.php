<?php

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource\Pages;

use Filament\Notifications\Notification;
use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Webkul\TimeOff\Enums\State;

class CreateMyTimeOff extends CreateRecord
{
    protected static string $resource = MyTimeOffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $employee = $user->employee;

        if ($employee) {
            $data['employee_id'] = $employee->id;
            $data['department_id'] = $employee->department->id;
        }


        if (isset($data['employee_id'])) {
            if ($employee->calendar) {
                $data['calendar_id'] = $employee->calendar->id;
                $data['number_of_hours'] = $employee->calendar->hours_per_day;
            }

            $user = $employee?->user;

            if ($user) {
                $data['user_id'] = $user->id;

                $data['company_id'] = $user->default_company_id;

                $data['employee_company_id'] = $user->default_company_id;
            }
        }


        if (isset($data['request_unit_half'])) {
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

        return $data;
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('time_off::filament/clusters/my-time/resources/my-time-off/pages/create-time-off.notification.title'))
            ->body(__('time_off::filament/clusters/my-time/resources/my-time-off/pages/create-time-off.notification.body'));
    }
}
