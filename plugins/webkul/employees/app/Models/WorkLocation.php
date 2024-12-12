<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Enums\WorkLocationEnum;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\Company;
use Webkul\Security\Models\User;

class WorkLocation extends Model
{
    use HasCustomFields, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'location_type',
        'location_number',
        'active',
    ];

    protected $casts = [
        'active'        => 'boolean',
        'location_type' => WorkLocationEnum::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active work locations.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
