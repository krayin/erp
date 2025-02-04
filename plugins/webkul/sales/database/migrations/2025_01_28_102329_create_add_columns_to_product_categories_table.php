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
        Schema::table('products_categories', function (Blueprint $table) {
            $table->json('product_properties_definition')->nullable()->comment('Product Properties Definition');
            $table->json('property_account_income_category_id')->nullable()->comment('Property Account Income Category Id');
            $table->json('property_account_expense_category_id')->nullable()->comment('Property Account Expense Category Id');
            $table->json('property_account_down_payment_category_id')->nullable()->comment('Property Account Down payment Category Id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_categories', function (Blueprint $table) {
            $table->dropColumn('product_properties_definition');
            $table->dropColumn('property_account_income_category_id');
            $table->dropColumn('property_account_expense_category_id');
            $table->dropColumn('property_account_down_payment_category_id');
        });
    }
};
