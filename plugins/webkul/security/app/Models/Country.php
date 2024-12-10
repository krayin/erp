<?php

namespace Webkul\Security\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'currency_id',
        'phone_code',
        'code',
        'name',
        'vat_label',
        'address_format',
        'state_required',
        'zip_required',
    ];
}
