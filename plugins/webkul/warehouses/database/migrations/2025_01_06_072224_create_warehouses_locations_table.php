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
        Schema::create('warehouses_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('position_x')->nullable()->comment('Corridor X');
            $table->integer('position_y')->nullable()->comment('Shelves Y');
            $table->integer('position_z')->nullable()->comment('Height Z');
            $table->string('type');
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->string('description')->nullable();
            $table->string('parent_path')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('cyclic_inventory_frequency')->nullable();
            $table->date('last_inventory_date')->nullable();
            $table->date('next_inventory_date')->nullable();
            $table->boolean('is_scrap')->nullable();
            $table->boolean('is_replenish')->nullable();
            $table->boolean('is_dock')->nullable();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('warehouses_locations')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('storage_category_id')
                ->nullable()
                ->constrained('warehouses_storage_categories')
                ->nullOnDelete();

            $table->foreignId('warehouse_id')
                ->nullable()
                ->constrained('warehouses_warehouses')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->unique(['company_id', 'barcode']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses_locations');
    }
};
