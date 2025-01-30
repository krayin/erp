<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Country;

class AccountTag extends Model
{
    use HasFactory;

    protected $table = 'invoices_account_tags';

    protected $fillable = [
        'color',
        'country_id',
        'creator_id',
        'applicability',
        'name',
        'is_active',
        'tax_negate',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
