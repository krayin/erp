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
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'tax_id',
        'company_id',
        'currency',
        'phone',
        'mobile',
        'email',
        'website',
        'email_domain',
        'color',
        'logo',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
