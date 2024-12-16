<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Database\Factories\DepartureReasonFactory;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;

class DepartureReason extends Model
{
    use HasCustomFields, HasFactory;

    protected $table = 'employees_departure_reasons';

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

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): DepartureReasonFactory
    {
        return DepartureReasonFactory::new();
    }
}
