<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Employee\Database\Factories\EmployeeFactory;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Bank;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\CompanyAddress;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;

class Employee extends Model
{
    use HasChatter, HasCustomFields, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'employees_employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'creator_id',
        'calendar_id',
        'department_id',
        'job_id',
        'partner_id',
        'work_location_id',
        'parent_id',
        'coach_id',
        'country_id',
        'state_id',
        'private_state_id',
        'private_country_id',
        'country_of_birth',
        'bank_account_id',
        'departure_reason_id',
        'name',
        'job_title',
        'work_phone',
        'mobile_phone',
        'color',
        'work_email',
        'children',
        'distance_home_work',
        'km_home_work',
        'distance_home_work_unit',
        'private_street1',
        'private_street2',
        'private_city',
        'private_zip',
        'private_phone',
        'private_email',
        'lang',
        'gender',
        'birthday',
        'marital',
        'spouse_complete_name',
        'spouse_birthdate',
        'place_of_birth',
        'ssnid',
        'sinid',
        'identification_id',
        'passport_id',
        'permit_no',
        'visa_no',
        'certificate',
        'study_field',
        'study_school',
        'emergency_contact',
        'emergency_phone',
        'employee_type',
        'barcode',
        'pin',
        'address_id',
        'tz',
        'work_permit',
        'leave_manager_id',
        'private_car_plate',
        'visa_expire',
        'work_permit_expiration_date',
        'departure_date',
        'departure_description',
        'additional_note',
        'notes',
        'is_active',
        'is_flexible',
        'is_fully_flexible',
        'work_permit_scheduled_activity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active'                      => 'boolean',
        'is_flexible'                    => 'boolean',
        'is_fully_flexible'              => 'boolean',
        'work_permit_scheduled_activity' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(EmployeeJobPosition::class, 'job_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function workLocation(): BelongsTo
    {
        return $this->belongsTo(WorkLocation::class, 'work_location_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'parent_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'coach_id');
    }

    public function privateState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'private_state_id');
    }

    public function privateCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'private_country_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function countryOfBirth(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_of_birth');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_account_id');
    }

    public function departureReason(): BelongsTo
    {
        return $this->belongsTo(DepartureReason::class, 'departure_reason_id');
    }

    public function employmentType(): BelongsTo
    {
        return $this->belongsTo(EmploymentType::class, 'employee_type');
    }

    public function categories()
    {
        return $this->belongsToMany(EmployeeCategory::class, 'employees_employee_categories', 'employee_id', 'category_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(EmployeeSkill::class, 'employee_id');
    }

    public function resumes()
    {
        return $this->hasMany(EmployeeResume::class, 'employee_id');
    }

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): EmployeeFactory
    {
        return EmployeeFactory::new();
    }

    public function leaveManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leave_manager_id');
    }

    public function companyAddress()
    {
        return $this->belongsTo(CompanyAddress::class, 'address_id');
    }
}
