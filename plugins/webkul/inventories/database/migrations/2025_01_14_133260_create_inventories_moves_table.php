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
        Schema::create('inventories_moves', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('state')->nullable();
            $table->string('origin')->nullable();
            $table->string('procure_method');
            $table->string('reference')->nullable();
            $table->text('description_picking')->nullable();
            $table->string('next_serial')->nullable();
            $table->integer('next_serial_count')->nullable();
            $table->boolean('is_favorite')->default(0);
            $table->decimal('product_qty', 15, 4)->nullable();
            $table->decimal('product_uom_qty', 15, 4)->nullable();
            $table->decimal('qty', 15, 4)->nullable();
            $table->boolean('is_picked')->default(0);
            $table->boolean('is_scraped')->default(0);
            $table->boolean('is_inventory')->default(0);
            $table->date('reservation_date')->nullable();
            $table->datetime('scheduled_at');
            $table->datetime('deadline')->nullable();
            $table->datetime('alert_Date')->nullable();

            $table->foreignId('operation_id')
                ->nullable()
                ->constrained('inventories_operations')
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->constrained('products_products')
                ->restrictOnDelete();

            $table->foreignId('uom_id')
                ->constrained('unit_of_measures')
                ->restrictOnDelete();

            $table->foreignId('source_location_id')
                ->constrained('inventories_locations')
                ->restrictOnDelete();

            $table->foreignId('destination_location_id')
                ->constrained('inventories_locations')
                ->restrictOnDelete();

            $table->foreignId('final_location_id')
                ->nullable()
                ->constrained('inventories_locations')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('scrap_id')
                ->nullable()
                ->constrained('inventories_scraps')
                ->nullOnDelete();

            $table->foreignId('rule_id')
                ->nullable()
                ->constrained('inventories_rules')
                ->nullOnDelete();

            $table->foreignId('operation_type_id')
                ->nullable()
                ->constrained('inventories_operation_types')
                ->nullOnDelete();

            $table->foreignId('origin_returned_move_id')
                ->nullable()
                ->constrained('inventories_moves')
                ->nullOnDelete();

            $table->foreignId('restrict_partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('warehouse_id')
                ->nullable()
                ->constrained('inventories_warehouses')
                ->nullOnDelete();

            $table->foreignId('package_level_id')
                ->nullable()
                ->constrained('inventories_package_levels')
                ->nullOnDelete();

            // $table->foreignId('warehouse_order_point_id')
            //     ->constrained('inventories_warehouse_order_points')
            //     ->restrictOnDelete();

            $table->foreignId('product_packaging_id')
                ->nullable()
                ->constrained('products_packagings')
                ->nullOnDelete();

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
        Schema::dropIfExists('inventories_moves');
    }
};
