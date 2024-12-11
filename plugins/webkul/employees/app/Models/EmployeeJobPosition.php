<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\Company;
use Webkul\Security\Models\User;

class EmployeeJobPosition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_job_positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sequence',
        'name',
        'description',
        'requirements',
        'expected_employees',
        'no_of_employees',
        'status',
        'no_of_recruitment',
        'department_id',
        'company_id',
        'open_date',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Department Relationship
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Company Relationship
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Creator User Relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
