<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;

class BranchAddress extends Model
{
    protected $fillable = [
        'street1',
        'street2',
        'city',
        'zip',
        'is_primary',
        'state_id',
        'country_id',
        'company_id',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
