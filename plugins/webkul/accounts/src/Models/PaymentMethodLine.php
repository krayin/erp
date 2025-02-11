<?php

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodLine extends Model
{
    use HasFactory;

    protected $table = 'accounts_payment_method_lines';

    protected $fillable = [
        'sort',
        'payment_method_id',
        'payment_account_id',
        'journal_id',
        'name',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function paymentAccount()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
