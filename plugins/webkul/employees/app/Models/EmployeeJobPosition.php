<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Database\Factories\EmployeeJobPositionFactory;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class EmployeeJobPosition extends Model
{
    use HasCustomFields, HasFactory, SoftDeletes;

    protected $table = 'employees_job_positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'name',
        'description',
        'requirements',
        'expected_employees',
        'no_of_employee',
        'is_active',
        'no_of_recruitment',
        'department_id',
        'employment_type_id',
        'company_id',
        'creator_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id');
    }

    protected static function newFactory(): EmployeeJobPositionFactory
    {
        return EmployeeJobPositionFactory::new();
    }
}
