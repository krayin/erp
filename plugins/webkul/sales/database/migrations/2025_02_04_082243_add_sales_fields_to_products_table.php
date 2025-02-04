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
        Schema::table('products_products', function (Blueprint $table) {
            $table->foreignId('property_account_income_id')->nullable()->comment('Income Account')->constrained('accounts_accounts')->nullOnDelete();
            $table->foreignId('property_account_expense_id')->nullable()->comment('Expense Account')->constrained('accounts_accounts')->nullOnDelete();
            $table->string('image')->comment('Image')->nullable();
            $table->string('service_type')->comment('Service Type')->nullable();
            $table->string('sale_line_warn')->comment('Sale Line Warning');
            $table->text('expense_policy')->comment('Expense Policy')->nullable();
            $table->text('invoice_policy')->comment('Invoicing Policy');
            $table->boolean('sales_ok')->default(true)->comment('Can be Sold');
            $table->boolean('purchase_ok')->default(true)->comment('Can be Purchased');
            $table->string('sale_line_warn_msg')->comment('Sale Line Warning Message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_products', function (Blueprint $table) {
            $table->dropColumn('property_account_income_id');
            $table->dropColumn('property_account_expense_id');
            $table->dropColumn('service_type');
            $table->dropColumn('sale_line_warn');
            $table->dropColumn('expense_policy');
            $table->dropColumn('invoicing_policy');
            $table->dropColumn('sale_line_warn_msg');
            $table->dropColumn('sales_ok');
            $table->dropColumn('purchase_ok');
        });
    }
};
