<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Employee\Database\Factories\WorkLocationFactory;
use Webkul\Employee\Enums\WorkLocationEnum;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class WorkLocation extends Model
{
    use HasCustomFields, HasFactory, SoftDeletes;

    protected $table = 'employees_work_locations';

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

    /**
     * Get the factory instance for the model.
     */
    protected static function newFactory(): WorkLocationFactory
    {
        return WorkLocationFactory::new();
    }
}
