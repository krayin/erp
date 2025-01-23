<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Employee\Models\Employee;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

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
        'email_cc',
        'name',
        'email_from',
        'priority',
        'phone',
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
        'name'             => 'Candidate Name',
        'email_from'       => 'Email From',
        'phone',
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

    public function createEmployee()
    {
        $employee = $this->employee()->create([
            'name'          => $this->name,
            'user_id'       => $this->user_id,
            'department_id' => $this->department_id,
            'company_id'    => $this->company_id,
            'partner_id'    => $this->partner_id,
            'company_id'    => $this->company_id,
            'work_email'    => $this->email_from,
            'mobile_phone'  => $this->phone,
            'is_active'     => true,
        ]);

        $this->update([
            'employee_id' => $employee->id,
        ]);

        return $employee;
    }

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function (self $candidate) {
            if (! $candidate->partner_id) {
                $candidate->handlePartnerCreation($candidate);
            } else {
                $candidate->handlePartnerUpdation($candidate);
            }
        });
    }

    /**
     * Handle the creation of a partner.
     */
    private function handlePartnerCreation(self $candidate)
    {
        $partner = $candidate->partner()->create([
            'creator_id' => Auth::user()->id ?? $candidate->id,
            'sub_type'   => 'partner',
            'company_id' => $candidate->company_id,
            'phone'      => $candidate->phone,
            'email'      => $candidate->email_from,
            'name'       => $candidate->name,
        ]);

        $candidate->partner_id = $partner->id;
        $candidate->save();
    }

    /**
     * Handle the updation of a partner.
     */
    private function handlePartnerUpdation(self $candidate)
    {
        $partner = Partner::updateOrCreate(
            ['id' => $candidate->partner_id],
            [
                'creator_id' => Auth::user()->id ?? $candidate->id,
                'sub_type'   => 'partner',
                'company_id' => $candidate->company_id,
                'phone'      => $candidate->phone,
                'email'      => $candidate->email_from,
                'name'       => $candidate->name,
            ]
        );

        if ($candidate->partner_id !== $partner->id) {
            $candidate->partner_id = $partner->id;
            $candidate->save();
        }
    }
}
