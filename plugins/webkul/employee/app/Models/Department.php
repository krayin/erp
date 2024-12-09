<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\Company;
use Webkul\Security\Models\User;

class Department extends Model
{
    use HasChatter, HasCustomFields, HasFactory, HasLogActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'manager_id',
        'company_id',
        'color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function jobPositions(): HasMany
    // {
    //     return $this->hasMany(JobPosition::class);
    // }

    // public function employees(): HasMany
    // {
    //     return $this->hasMany(Employee::class);
    // }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function manager(): BelongsTo
    {
        // Need to ask sir that manager will be user or employee
        return $this->belongsTo(User::class);
    }
}
