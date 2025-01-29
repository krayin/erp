<?php

namespace Webkul\Invoice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Invoice\Enums\DelayType;
use Webkul\Invoice\Enums\DueTermValue;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class PaymentTerm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoices_payment_terms';

    protected $fillable = [
        'company_id',
        'sort',
        'discount_days',
        'creator_id',
        'early_pay_discount',
        'name',
        'note',
        'is_active',
        'display_on_invoice',
        'early_discount',
        'discount_percentage',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function dueTerm()
    {
        return $this->hasMany(PaymentDueTerm::class, 'payment_id');
    }

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($paymentTerm) {
            $paymentTerm->dueTerm()->create([
                'value'           => DueTermValue::PERCENT->value,
                'value_amount'    => 100,
                'delay_type'      => DelayType::DAYS_AFTER->value,
                'days_next_month' => 10,
                'nb_days'         => 0,
            ]);
        });
    }
}
