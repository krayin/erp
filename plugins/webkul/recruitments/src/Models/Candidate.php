<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Employee\Models\Employee;
use Webkul\Partner\Models\Partner;
use Webkul\Recruitment\Models\Degree;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class Candidate extends Model
{
    use HasChatter, HasLogActivity, SoftDeletes;

    protected $table = 'recruitments_candidates';

    protected $fillable = [
        'message_bounced',
        'company_id',
        'partner_id',
        'degree_id',
        'manager_id',
        'employee_id',
        'creator_id',
        'phone_sanitized',
        'email_normalized',
        'email_cc',
        'partner_name',
        'email_from',
        'partner_phone',
        'partner_phone_sanitized',
        'linkedin_profile',
        'availability_date',
        'candidate_properties',
        'is_active',
    ];

    protected array $logAttributes = [
        'company.name'     => 'Company',
        'partner.name'     => 'Contact',
        'degree.name'      => 'Degree',
        'user.name'        => 'Manager',
        'employee.name'    => 'Employee',
        'creator.name'     => 'Created By',
        'phone_sanitized'  => 'Phone',
        'email_normalized' => 'Email',
        'email_cc'         => 'Email CC',
        'partner_name'     => 'Candidate Name',
        'email_from'       => 'Email From',
        'partner_phone',
        'partner_phone_sanitized',
        'linkedin_profile',
        'availability_date',
        'is_active' => 'Status',
    ];

    protected $casts = [
        'candidate_properties' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class, 'degree_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function categories()
    {
        return $this->belongsToMany(ApplicantCategory::class, 'recruitments_candidate_applicant_categories', 'candidate_id', 'category_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CandidateSkill::class, 'candidate_id');
    }
}
