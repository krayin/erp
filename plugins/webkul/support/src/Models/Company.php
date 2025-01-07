<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Database\Factories\CompanyFactory;

class Company extends Model
{
    use HasChatter, HasCustomFields, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'name',
        'company_id',
        'parent_id',
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
        'website',
    ];

    /**
     * Get the user associated with the company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent company
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    /**
     * Get the branches (child companies)
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    /**
     * Scope a query to only include parent companies
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if company is a branch
     */
    public function isBranch(): bool
    {
        return ! is_null($this->parent_id);
    }

    /**
     * Check if company is a parent
     */
    public function isParent(): bool
    {
        return is_null($this->parent_id);
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
