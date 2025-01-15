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
        Schema::create('products_products', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('service_tracking')->default('none');
            $table->string('reference')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('price', 15, 4)->nullable();
            $table->decimal('cost', 15, 4)->nullable();
            $table->decimal('volume', 15, 4)->nullable();
            $table->decimal('weight', 15, 4)->nullable();
            $table->text('description')->nullable();
            $table->text('description_purchase')->nullable();
            $table->text('description_sale')->nullable();
            $table->boolean('enable_sales')->nullable();
            $table->boolean('enable_purchase')->nullable();
            $table->boolean('is_favorite')->nullable();
            $table->boolean('is_configurable')->nullable();
            $table->integer('sort')->nullable();

            $table->foreignId('parent_id')
                ->constrained('products_categories')
                ->restrictOnDelete();

            $table->foreignId('uom_id')
                ->constrained('unit_of_measures')
                ->restrictOnDelete();

            $table->foreignId('uom_po_id')
                ->constrained('unit_of_measures')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_products');
    }
};
