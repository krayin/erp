<?php

namespace Webkul\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\State;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sequence',
        'name',
        'street1',
        'street2',
        'city',
        'state_id',
        'zip',
        'country_id',
        'tax_id',
        'company_id',
        'user_id',
        'currency_id',
        'phone',
        'mobile',
        'email',
        'founded_date',
        'color',
        'logo',
        'registration_number',
        'accounting_reference',
        'is_active',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the currency associated with the company.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the state associated with the company.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the country associated with the company.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
