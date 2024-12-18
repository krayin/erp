<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Database\Factories\EmploymentTypeFactory;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Country;

class EmploymentType extends Model
{
    use HasCustomFields, HasFactory;

    protected $table = 'employees_employment_types';

    protected $fillable = [
        'name',
        'country_id',
        'creator_id',
        'code',
        'sort',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): EmploymentTypeFactory
    {
        return EmploymentTypeFactory::new();
    }
}
