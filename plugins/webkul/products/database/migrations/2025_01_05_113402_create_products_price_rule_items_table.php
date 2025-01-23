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
        Schema::create('products_price_rule_items', function (Blueprint $table) {
            $table->id();
            $table->string('apply_to');
            $table->string('display_apply_to');
            $table->string('base');
            $table->string('type');
            $table->decimal('min_quantity', 15, 4)->nullable();
            $table->decimal('fixed_price', 15, 4)->nullable();
            $table->decimal('price_discount', 15, 4)->nullable();
            $table->decimal('price_round', 15, 4)->nullable();
            $table->decimal('price_surcharge', 15, 4)->nullable();
            $table->decimal('price_markup', 15, 4)->nullable();
            $table->decimal('price_min_margin', 15, 4)->nullable();
            $table->double('percent_price')->nullable();
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();

            $table->foreignId('price_rule_id')
                ->constrained('products_price_rules')
                ->cascadeOnDelete();

            $table->foreignId('base_price_rule_id')
                ->nullable()
                ->constrained('products_price_rules')
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('products_categories')
                ->cascadeOnDelete();

            $table->foreignId('currency_id')
                ->nullable()
                ->constrained('currencies')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

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
        Schema::dropIfExists('products_price_rule_items');
    }
};
