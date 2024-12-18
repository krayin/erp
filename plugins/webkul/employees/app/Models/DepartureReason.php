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
        'sort',
        'reason_code',
        'creator_id',
        'name',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
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
