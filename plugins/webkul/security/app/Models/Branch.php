<?php

namespace Webkul\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
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
        'company_id',
        'currency_code',
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
}
