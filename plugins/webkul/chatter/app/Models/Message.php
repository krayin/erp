<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Security\Models\User;
use Webkul\Support\Models\ActivityType;
use Webkul\Support\Models\Company;

class Message extends Model
{
    protected $table = 'chatter_messages';

    protected $fillable = [
        'company_id',
        'activity_type_id',
        'messageable_type',
        'messageable_id',
        'creator_id',
        'type',
        'name',
        'subject',
        'body',
        'is_internal',
        'date',
        'pinned_at',
        'log_name',
        'description',
        'event',
        'causer_type',
        'causer_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function getPropertiesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPropertiesAttribute($value)
    {
        $this->attributes['properties'] = json_encode($value);
    }

    public function getBodyAttribute($value)
    {
        return json_decode($value, true);
    }
}
