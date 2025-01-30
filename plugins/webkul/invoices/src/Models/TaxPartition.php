<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Support\Models\Company;

class TaxPartition extends Model
{
    use HasFactory;

    protected $table = 'invoices_tax_partitions';

    protected $fillable = [
        'account_id',
        'tax_id',
        'company_id',
        'sort',
        'repartition_type',
        'document_type',
        'use_in_tax_closing',
        'factor_percent',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
