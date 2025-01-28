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
        Schema::create('sales_product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('sales_product_categories')->cascadeOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->comment('Name');
            $table->string('complete_name')->nullable()->comment('Complete Name');
            $table->string('parent_path')->nullable()->comment('Parent Path');
            $table->json('product_properties_definition')->nullable()->comment('Product Properties Definition');
            $table->json('property_account_income_category_id')->nullable()->comment('Property Account Income Category Id');
            $table->json('property_account_expense_category_id')->nullable()->comment('Property Account Expense Category Id');
            $table->json('property_account_down_payment_category_id')->nullable()->comment('Property Account Down payment Category Id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_product_categories');
    }
};
