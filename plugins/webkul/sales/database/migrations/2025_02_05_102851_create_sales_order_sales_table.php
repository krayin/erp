<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales_order_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable()->comment('Sort Order');
            $table->foreignId('order_id')->comment('Order Reference')->constrained('sales_orders')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->comment('Company Reference')->constrained('companies')->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->comment('Currency Reference')->constrained('currencies')->nullOnDelete();
            $table->foreignId('order_partner_id')->nullable()->comment('Order Partner Reference')->constrained('partners_partners')->nullOnDelete();
            $table->foreignId('salesman_id')->nullable()->comment('Salesman Reference')->constrained('users')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->comment('Product Reference')->constrained('products_products')->nullOnDelete();
            $table->foreignId('product_uom_id')->nullable()->comment('Product UOM Reference')->constrained('unit_of_measures')->nullOnDelete();
            $table->foreignId('linked_sale_order_sale_id')->nullable()->comment('Linked Sale Order Sale Reference')->constrained('sales_order_sales')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->comment('Creator Reference')->constrained('users')->nullOnDelete();
            $table->string('state')->nullable()->comment('Order State');
            $table->string('display_type')->nullable()->comment('Display Type');
            $table->string('virtual_id')->nullable()->comment('Virtual ID');
            $table->string('linked_virtual_id')->nullable()->comment('Linked Virtual ID');
            $table->string('qty_delivered_method')->nullable()->comment('Quantity Delivered Method');
            $table->string('invoice_status')->nullable()->comment('Invoice Status');
            $table->string('analytic_distribution')->nullable()->comment('Analytic Distribution Status');
            $table->string('name')->comment('Name');
            $table->double('product_uom_qty')->comment('Product UOM Quantity');
            $table->double('price_unit')->comment('Price Unit');
            $table->double('discount')->nullable()->comment('Discount');
            $table->double('price_subtotal')->nullable()->comment('Price Subtotal');
            $table->double('price_total')->nullable()->comment('Price Total');
            $table->double('price_reduce_taxexcl')->nullable()->comment('Price Reduce Tax excl');
            $table->double('price_reduce_taxinc')->nullable()->comment('Price Reduce Tax incl');
            $table->double('qty_delivered')->nullable()->comment('Delivery Quantity');
            $table->double('qty_invoiced')->nullable()->comment('Invoiced Quantity');
            $table->double('qty_to_invoice')->nullable()->comment('Quantity To Invoice');
            $table->double('untaxed_amount_invoiced')->nullable()->comment('Untaxed Invoiced Amount');
            $table->double('untaxed_amount_to_invoice')->nullable()->comment('Untaxed Amount To Invoice');
            $table->boolean('is_downpayment')->nullable()->comment('Is a down payment');
            $table->boolean('is_expense')->nullable()->comment('Is expense');
            $table->timestamp('create_date')->nullable()->comment('Created on');
            $table->timestamp('write_date')->nullable()->comment('Last Updated on');
            $table->double('technical_price_unit')->nullable()->comment('Technical Price Unit');
            $table->double('price_tax')->nullable()->comment('Total Tax');
            $table->double('product_packaging_qty')->nullable()->comment('Packaging Quantity');
            $table->double('customer_lead')->comment('Lead Time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_sales');
    }
};
