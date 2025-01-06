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
        Schema::create('products_product_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();

            $table->foreignId('product_id')
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->foreignId('attribute_id')
                ->constrained('products_attributes')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_product_attributes');
    }
};
