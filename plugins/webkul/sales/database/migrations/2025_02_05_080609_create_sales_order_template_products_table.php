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
        Schema::create('sales_order_template_products', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable()->comment('Sort');
            $table->foreignId('order_template_id')->comment('Order Template')->constrained('sales_order_templates')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->comment('Product')->constrained('products_products')->nullOnDelete();
            $table->foreignId('product_uom_id')->nullable()->comment('UOM')->constrained('unit_of_measures')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->comment('Creator')->constrained('users')->nullOnDelete();
            $table->string('display_type')->nullable()->comment('Display Type');
            $table->string('name')->nullable()->comment('Name');
            $table->double('quantity')->comment('Quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_template_products');
    }
};
