<?php

namespace Webkul\Sale\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class OrderTemplate extends Model
{
    use HasFactory;

    protected $table = 'sales_order_templates';

    protected $fillable = [
        'sort',
        'company_id',
        'number_of_days',
        'creator_id',
        'name',
        'note',
        'journal_id',
        'is_active',
        'require_signature',
        'require_payment',
        'prepayment_percentage',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
