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
        Schema::create('inventories_move_lines', function (Blueprint $table) {
            $table->id();
            $table->string('lot_name')->nullable();
            $table->string('state')->nullable();
            $table->string('reference')->nullable();
            $table->string('picking_description')->nullable();
            $table->decimal('quantity', 15, 4)->nullable();
            $table->decimal('quantity_product_uom', 15, 4)->nullable();
            $table->boolean('is_picked')->default(0);
            $table->datetime('scheduled_at');

            $table->foreignId('move_id')
                ->nullable()
                ->constrained('inventories_moves')
                ->nullOnDelete();

            $table->foreignId('operation_id')
                ->nullable()
                ->constrained('inventories_operations')
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->foreignId('uom_id')
                ->constrained('unit_of_measures')
                ->restrictOnDelete();

            $table->foreignId('package_id')
                ->nullable()
                ->constrained('inventories_packages')
                ->restrictOnDelete();

            $table->foreignId('result_package_id')
                ->nullable()
                ->constrained('inventories_packages')
                ->restrictOnDelete();

            $table->foreignId('package_level_id')
                ->nullable()
                ->constrained('inventories_package_levels')
                ->nullOnDelete();

            $table->foreignId('lot_id')
                ->nullable()
                ->constrained('inventories_lots')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('source_location_id')
                ->constrained('inventories_locations')
                ->restrictOnDelete();

            $table->foreignId('destination_location_id')
                ->constrained('inventories_locations')
                ->restrictOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

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
        Schema::dropIfExists('inventories_move_lines');
    }
};
