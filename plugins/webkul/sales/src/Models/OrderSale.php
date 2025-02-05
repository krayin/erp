<?php

namespace Webkul\Sale\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\UOM;

class OrderSale extends Model
{
    protected $table = 'sales_order_sales';

    protected $fillable = [
        'sort',
        'order_id',
        'company_id',
        'currency_id',
        'order_partner_id',
        'salesman_id',
        'product_id',
        'product_uom_id',
        'linked_sale_order_sale_id',
        'creator_id',
        'state',
        'display_type',
        'virtual_id',
        'linked_virtual_id',
        'qty_delivered_method',
        'invoice_status',
        'analytic_distribution',
        'name',
        'product_uom_qty',
        'price_unit',
        'discount',
        'price_subtotal',
        'price_total',
        'price_reduce_taxexcl',
        'price_reduce_taxinc',
        'qty_delivered',
        'qty_invoiced',
        'qty_to_invoice',
        'untaxed_amount_invoiced',
        'untaxed_amount_to_invoice',
        'is_downpayment',
        'is_expense',
        'create_date',
        'write_date',
        'technical_price_unit',
        'price_tax',
        'product_packaging_qty',
        'customer_lead',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function orderPartner()
    {
        return $this->belongsTo(Partner::class, 'order_partner_id');
    }

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productUom()
    {
        return $this->belongsTo(UOM::class, 'product_uom_id');
    }

    public function linkedSaleOrderSale()
    {
        return $this->belongsTo(OrderSale::class, 'linked_sale_order_sale_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
