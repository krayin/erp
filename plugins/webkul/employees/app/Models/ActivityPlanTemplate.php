<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Security\Models\User;

class ActivityPlanTemplate extends Model
{
    use HasFactory;

    protected $table = 'employees_activity_plan_templates';

    protected $fillable = [
        'delay_count',
        'sort',
        'delay_unit',
        'delay_from',
        'summary',
        'responsible_type',
        'note',
        'plan_id',
        'activity_type_id',
        'responsible_id',
        'creator_id',
    ];

    /**
     * Relationships
     */
    public function activityPlan(): BelongsTo
    {
        return $this->belongsTo(ActivityPlan::class, 'plan_id');
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function responsible(): MorphTo
    {
        return $this->morphTo('responsible', 'responsible_type', 'responsible_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
