<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Model;

class JournalAccount extends Model
{
    protected $table = 'journal_accounts';

    protected $timestamps = false;

    protected $fillable = [
        'account_id',
        'journal_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
