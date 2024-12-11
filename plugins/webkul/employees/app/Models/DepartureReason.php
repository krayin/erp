<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

class DepartureReason extends Model
{
    protected $fillable = [
        'sequence',
        'reason_code',
        'name',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class);
    }

    // public function departureWizards()
    // {
    //     return $this->hasMany(HrDepartureWizard::class, 'departure_reason_id');
    // }

    // public function employees()
    // {
    //     return $this->hasMany(HrEmployee::class, 'departure_reason_id');
    // }
}
