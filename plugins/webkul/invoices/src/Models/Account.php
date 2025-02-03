<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Invoice\Enums\AccountType;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Currency;

class Account extends Model
{
    use HasFactory;

    protected $table = 'invoices_accounts';

    protected $fillable = [
        'currency_id',
        'creator_id',
        'account_type',
        'name',
        'code',
        'note',
        'deprecated',
        'reconcile',
        'non_trade',
    ];

    protected $casts = [
        'deprecated'   => 'boolean',
        'reconcile'    => 'boolean',
        'non_trade'    => 'boolean',
        'account_type' => AccountType::class,
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'invoices_account_taxes', 'account_id', 'tax_id');
    }

    public function tags()
    {
        return $this->belongsToMany(AccountTag::class, 'invoices_account_account_tags', 'account_id', 'account_tag_id');
    }

    public function journals()
    {
        return $this->belongsToMany(Journal::class, 'invoices_account_journals', 'account_id', 'journal_id');
    }
}
