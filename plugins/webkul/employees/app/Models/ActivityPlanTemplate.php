<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

class ActivityPlanTemplate extends Model
{
    protected $fillable = [
        'plan_id',
        'sequence',
        'activity_type_id',
        'delay_count',
        'responsible_id',
        'create_uid',
        'write_uid',
        'delay_unit',
        'delay_from',
        'summary',
        'responsible_type',
        'note',
    ];

    /**
     * Get the plan associated with the activity.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(ActivityPlan::class);
    }

    /**
     * Get the activity type associated with the template.
     */
    public function activity_type(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    /**
     * Get the responsible user.
     */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    /**
     * Get the creator user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
