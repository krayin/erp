<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Database\Factories\CalendarAttendanceFactory;
use Webkul\Security\Models\User;

class CalendarAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence',
        'name',
        'day_of_week',
        'day_period',
        'week_type',
        'display_type',
        'date_from',
        'date_to',
        'hour_from',
        'hour_to',
        'durations_days',
        'calendar_id',
        'user_id',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): CalendarAttendanceFactory
    {
        return CalendarAttendanceFactory::new();
    }
}
