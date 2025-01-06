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
        Schema::create('warehouses_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->string('name');
            $table->boolean('product_selectable')->nullable();
            $table->boolean('product_category_selectable')->nullable();
            $table->boolean('warehouse_selectable')->nullable();
            $table->boolean('packaging_selectable')->nullable();

            $table->foreignId('supplied_warehouse_id')
                ->nullable()
                ->constrained('warehouses_warehouses')
                ->nullOnDelete();

            $table->foreignId('supplier_warehouse_id')
                ->nullable()
                ->constrained('warehouses_warehouses')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

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
        Schema::dropIfExists('warehouses_routes');
    }
};
