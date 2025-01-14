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
        Schema::create('products_product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->decimal('extra_price', 15, 4)->nullable();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->foreignId('attribute_id')
                ->nullable()
                ->constrained('products_attributes')
                ->cascadeOnDelete();

            $table->foreignId('product_attribute_id')
                ->constrained('products_product_attributes')
                ->cascadeOnDelete();

            $table->foreignId('attribute_option_id')
                ->constrained('products_attribute_options')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_product_attribute_values');
    }
};
