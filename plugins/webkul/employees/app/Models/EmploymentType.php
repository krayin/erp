<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Employee\Database\Factories\EmploymentTypeFactory;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class EmploymentType extends Model
{
    use HasCustomFields, HasFactory;

    protected $table = 'employees_employment_types';

    protected $fillable = [
        'name',
        'company_id',
        'user_id',
        'code',
        'sort',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): EmploymentTypeFactory
    {
        return EmploymentTypeFactory::new();
    }
}
