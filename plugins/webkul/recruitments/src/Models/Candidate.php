<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
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
        'user_id',
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
        'priority',
        'availability_date',
        'candidate_properties',
        'is_active',
        'color',
    ];

    protected array $logAttributes = [
        'message_bounced',
        'company.name',
        'partner.name',
        'degree.name',
        'user.name',
        'employee.name',
        'creator.name',
        'phone_sanitized',
        'email_normalized',
        'email_cc',
        'partner_name',
        'email_from',
        'partner_phone',
        'partner_phone_sanitized',
        'linkedin_profile',
        'priority',
        'availability_date',
        'is_active' => 'Status',
        'color',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
