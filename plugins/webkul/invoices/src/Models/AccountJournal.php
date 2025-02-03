<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Model;

class AccountJournal extends Model
{
    protected $table = 'invoices_account_journals';

    protected $fillable = [
        'account_id',
        'journal_id',
    ];

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
