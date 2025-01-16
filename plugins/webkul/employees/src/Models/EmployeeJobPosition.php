<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Employee\Database\Factories\EmployeeJobPositionFactory;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Partner\Models\Industry;
use Webkul\Partner\Models\Partner;
use Webkul\Recruitment\Models\Applicant;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class EmployeeJobPosition extends Model implements Sortable
{
    use HasCustomFields, HasFactory, SoftDeletes, SortableTrait;

    protected $table = 'employees_job_positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'address_id',
        'manager_id',
        'industry_id',
        'expected_employees',
        'no_of_employee',
        'no_of_recruitment',
        'department_id',
        'company_id',
        'creator_id',
        'employment_type_id',
        'recruiter_id',
        'no_of_hired_employee',
        'date_from',
        'date_to',
        'name',
        'description',
        'requirements',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'address_id')->where('sub_type', 'company');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_position_skills', 'job_position_id', 'skill_id');
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'job_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id');
    }

    protected static function newFactory(): EmployeeJobPositionFactory
    {
        return EmployeeJobPositionFactory::new();
    }

    protected function noOfEmployee(): Attribute
    {
        return Attribute::make(
            get: function () {
                return once(function () {
                    return $this->employees()
                        ->where('is_active', true)
                        ->count();
                });
            }
        );
    }

    protected function noOfHiredEmployee(): Attribute
    {
        return Attribute::make(
            get: function () {
                return once(function () {
                    return $this->applications()
                        ->where(function ($query) {
                            $query->whereNotNull('date_closed')
                                ->where('is_active', true);
                        })
                        ->count();
                });
            }
        );
    }

    protected function expectedEmployees(): Attribute
    {
        return Attribute::make(
            get: function () {
                $currentEmployees = $this->getAttributeValue('no_of_employee');

                return $currentEmployees + ($this->no_of_recruitment ?? 0);
            }
        );
    }

    public function applications()
    {
        return $this->hasMany(Applicant::class, 'job_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($jobPosition) {
            cache()->forget("job_position_{$jobPosition->id}_employee_count");
            cache()->forget("job_position_{$jobPosition->id}_hired_count");
        });
    }
}
