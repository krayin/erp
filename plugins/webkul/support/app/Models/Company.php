<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;

class Company extends Model
{
    use HasChatter, HasCustomFields, HasFactory, HasLogActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sequence',
        'name',
        'company_id',
        'tax_id',
        'registration_number',
        'email',
        'phone',
        'mobile',
        'logo',
        'color',
        'is_active',
        'founded_date',
        'user_id',
        'currency_id',
    ];

    /**
     * Get the user associated with the company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branches associated with the company.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the currency associated with the company.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Scope a query to only include active companies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function address()
    {
        return $this->hasOne(CompanyAddress::class);
    }

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }
}
