<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'invoices_taxes';

    protected $fillable = [
        'sort',
        'company_id',
        'tax_group_id',
        'cash_basis_transition_account_id',
        'country_id',
        'creator_id',
        'type_tax_use',
        'tax_scope',
        'amount_type',
        'price_include_override',
        'tax_exigibility',
        'name',
        'description',
        'invoice_label',
        'invoice_legal_notes',
        'amount',
        'is_active',
        'include_base_amount',
        'is_base_affected',
        'analytic',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function taxGroup()
    {
        return $this->belongsTo(TaxGroup::class, 'tax_group_id');
    }

    public function cashBasisTransitionAccount()
    {
        return $this->belongsTo(Account::class, 'cash_basis_transition_account_id');
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
