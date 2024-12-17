<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTypeSuggestion extends Model
{
    protected $table = 'activity_type_suggestions';

    public $timestamps = false;

    protected $fillable = [
        'activity_type_id',
        'suggested_activity_type_id',
    ];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function suggestedActivityType()
    {
        return $this->belongsTo(ActivityType::class, 'suggested_activity_type_id');
    }
}
