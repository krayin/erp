<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Model;

class TaxTaxRelation extends Model
{
    protected $table = 'invoices_tax_tax_relations';

    protected $fillable = ['parent_tax_id', 'child_tax_id'];

    public $timestamps = false;

    public function parentTax()
    {
        return $this->belongsTo(Tax::class, 'parent_tax_id');
    }

    public function childTax()
    {
        return $this->belongsTo(Tax::class, 'child_tax_id');
    }
}
