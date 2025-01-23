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
        Schema::create('inventories_storage_category_capacities', function (Blueprint $table) {
            $table->id();
            $table->decimal('qty', 15, 4)->default(0);

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('storage_category_id');
            $table->foreign('storage_category_id', 'fk_storage_category')
                ->references('id')
                ->on('inventories_storage_categories')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('package_type_id')->nullable();
            $table->foreign('package_type_id', 'fk_package_type')
                ->references('id')
                ->on('inventories_package_types')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->unique(['product_id', 'storage_category_id'], 'unique_product_storage_category');
            $table->unique(['package_type_id', 'storage_category_id'], 'unique_package_type_storage_category');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_storage_category_capacities');
    }
};
