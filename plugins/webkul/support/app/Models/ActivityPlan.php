<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Models\Department;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;

class ActivityPlan extends Model
{
    use HasCustomFields, HasFactory, SoftDeletes;

    protected $table = 'activity_plans';

    protected $fillable = [
        'company_id',
        'plugin',
        'creator_id',
        'name',
        'department_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function activityTypes(): HasMany
    {
        return $this->hasMany(ActivityType::class, 'activity_plan_id');
    }

    public function activityPlanTemplates(): HasMany
    {
        return $this->hasMany(ActivityPlanTemplate::class, 'plan_id');
    }
}
