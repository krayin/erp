<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\ActivityType;

class EmployeeActivity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'request_partner_id',
        'recommended_activity_type_id',
        'previous_activity_type_id',
        'activity_type_id',
        'user_id',
        'creator_id',
        'name',
        'summary',
        'user_tz',
        'date_deadline',
        'date_done',
        'note',
        'automated',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'automated'     => 'boolean',
        'active'        => 'boolean',
        'date_deadline' => 'date',
        'date_done'     => 'date',
    ];

    /**
     * Relationships
     */

    /**
     * Get the parent model (polymorphic relationship).
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the partner who requested the activity.
     */
    public function requestPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'request_partner_id');
    }

    /**
     * Get the recommended activity type.
     */
    public function recommendedActivityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'recommended_activity_type_id');
    }

    /**
     * Get the previous activity type.
     */
    public function previousActivityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'previous_activity_type_id');
    }

    /**
     * Get the activity type.
     */
    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    /**
     * Get the user who performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created the activity.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
