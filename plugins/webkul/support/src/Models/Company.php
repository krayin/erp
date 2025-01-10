<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Partner\Models\Partner;
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
        'creator_id',
        'currency_id',
        'website',
    ];

    /**
     * Get the creator of the company
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
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

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
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

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($company) {
            if (! $company->partner_id) {
                $company->handlePartnerCreation($company);
            } else {
                $company->handlePartnerUpdation($company);
            }
        });
    }

    /**
     * Handle the creation of a partner.
     */
    private function handlePartnerCreation(self $company)
    {
        $partner = $company->partner()->create([
            'creator_id'       => $company->creator_id ?? Auth::id(),
            'sub_type'         => 'company',
            'company_registry' => $company->registration_number,
            'name'             => $company->name,
            'email'            => $company->email,
            'website'          => $company->website,
            'tax_id'           => $company->tax_id,
            'phone'            => $company->phone,
            'mobile'           => $company->mobile,
            'color'            => $company->color,
            'parent_id'        => $company->parent_id,
            'company_id'       => $company->id,
        ]);

        $company->partner_id = $partner->id;
        $company->save();
    }

    /**
     * Handle the updation of a partner.
     */
    private function handlePartnerUpdation(self $company)
    {
        $partner = Partner::updateOrCreate(
            ['id' => $company->partner_id],
            [
                'creator_id'       => $company->creator_id ?? Auth::id(),
                'sub_type'         => 'company',
                'company_registry' => $company->registration_number,
                'name'             => $company->name,
                'email'            => $company->email,
                'website'          => $company->website,
                'tax_id'           => $company->tax_id,
                'phone'            => $company->phone,
                'mobile'           => $company->mobile,
                'color'            => $company->color,
                'parent_id'        => $company->parent_id,
                'company_id'       => $company->id,
            ]
        );

        if ($company->partner_id !== $partner->id) {
            $company->partner_id = $partner->id;
            $company->save();
        }
    }
}
