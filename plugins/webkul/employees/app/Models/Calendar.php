<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Calendar extends Model
{
    use HasCustomFields;

    protected $table = 'calendars';

    protected $fillable = [
        'name',
        'tz',
        'hours_per_day',
        'status',
        'two_weeks_calendar',
        'flexible_hours',
        'full_time_required_hours',
        'user_id',
        'company_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attendance()
    {
        return $this->hasMany(CalendarAttendance::class);
    }
}
