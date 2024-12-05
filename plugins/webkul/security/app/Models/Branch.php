<?php

namespace Webkul\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'tax_id',
        'branch_id',
        'currency',
        'phone',
        'mobile',
        'email',
        'logo',
        'company_id',
        'parent_branch_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parentBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'parent_branch_id');
    }

    public function childBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'parent_branch_id');
    }
}
