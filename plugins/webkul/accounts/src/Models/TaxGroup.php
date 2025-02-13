<?php

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;

class TaxGroup extends Model
{
    use HasFactory;

    protected $table = 'accounts_tax_groups';

    protected $fillable = [
        'sort',
        'company_id',
        'country_id',
        'creator_id',
        'name',
        'preceding_subtotal',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
