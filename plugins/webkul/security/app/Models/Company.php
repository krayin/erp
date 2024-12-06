<?php

namespace Webkul\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class Company extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'company_id',
        'user_id',
        'tax_id',
        'registration_number',
        'email',
        'phone',
        'mobile',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'logo',
        'color',
        'currency_code',
        'accounting_reference',
        'is_active',
        'founded_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
