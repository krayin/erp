<?php

namespace Webkul\Sale\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Account\Models\FiscalPosition;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\PaymentTerm;
use Webkul\Partner\Models\Partner;
use Webkul\Recruitment\Models\UTMMedium;
use Webkul\Recruitment\Models\UTMSource;
use Webkul\Sale\Enums\OrderDisplayType;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

class Order extends Model
{
    use HasFactory;

    protected $table = 'sales_orders';

    protected $fillable = [
        'utm_source_id',
        'medium_id',
        'company_id',
        'partner_id',
        'journal_id',
        'partner_invoice_id',
        'partner_shipping_id',
        'fiscal_position_id',
        'sale_order_template_id',
        'payment_term_id',
        'currency_id',
        'user_id',
        'team_id',
        'creator_id',
        'access_token',
        'name',
        'state',
        'client_order_ref',
        'origin',
        'reference',
        'signed_by',
        'invoice_status',
        'validity_date',
        'note',
        'currency_rate',
        'amount_untaxed',
        'amount_tax',
        'amount_total',
        'locked',
        'require_signature',
        'require_payment',
        'create_date',
        'commitment_date',
        'date_order',
        'signed_on',
        'prepayment_percent'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function partnerInvoice()
    {
        return $this->belongsTo(Partner::class, 'partner_invoice_id');
    }

    public function partnerShipping()
    {
        return $this->belongsTo(Partner::class, 'partner_shipping_id');
    }

    public function fiscalPosition()
    {
        return $this->belongsTo(FiscalPosition::class);
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function utmSource()
    {
        return $this->belongsTo(UTMSource::class);
    }

    public function medium()
    {
        return $this->belongsTo(UTMMedium::class);
    }

    public function orderSalesProducts()
    {
        return $this
            ->hasMany(OrderSale::class)
            ->whereNull('display_type');
    }

    public function orderSalesSections()
    {
        return $this
            ->hasMany(OrderSale::class)
            ->where('display_type', OrderDisplayType::SECTION->value);
    }

    public function orderSalesNotes()
    {
        return $this
            ->hasMany(OrderSale::class)
            ->where('display_type', OrderDisplayType::NOTE->value);
    }

    public function quotationTemplate()
    {
        return $this->belongsTo(OrderTemplate::class, 'sale_order_template_id');
    }
}
