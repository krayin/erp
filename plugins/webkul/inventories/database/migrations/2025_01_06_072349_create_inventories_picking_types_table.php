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
        Schema::create('inventories_picking_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->integer('sort')->nullable();
            $table->string('sequence_code');
            $table->string('reservation_method');
            $table->integer('reservation_days_before');
            $table->integer('reservation_days_before_priority');
            $table->string('product_label_format')->nullable();
            $table->string('lot_label_format')->nullable();
            $table->string('package_label_to_print')->nullable();
            $table->string('barcode')->nullable();
            $table->string('create_backorder');
            $table->string('move_type')->nullable();
            $table->boolean('show_entire_packs')->nullable();
            $table->boolean('use_create_lots')->nullable();
            $table->boolean('use_existing_lots')->nullable();
            $table->boolean('print_label')->nullable();
            $table->boolean('show_operations')->nullable();
            $table->boolean('auto_show_reception_report')->nullable();
            $table->boolean('auto_print_delivery_slip')->nullable();
            $table->boolean('auto_print_return_slip')->nullable();
            $table->boolean('auto_print_product_labels')->nullable();
            $table->boolean('auto_print_lot_labels')->nullable();
            $table->boolean('auto_print_reception_report')->nullable();
            $table->boolean('auto_print_reception_report_labels')->nullable();
            $table->boolean('auto_print_packages')->nullable();
            $table->boolean('auto_print_package_label')->nullable();

            $table->foreignId('return_picking_type_id')
                ->nullable()
                ->constrained('inventories_picking_types')
                ->nullOnDelete();

            $table->foreignId('source_location_id')
                ->constrained('inventories_locations')
                ->restrictOnDelete();

            $table->foreignId('destination_location_id')
                ->constrained('inventories_locations')
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->nullable()
                ->constrained('inventories_warehouses')
                ->cascadeOnDelete();

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
        Schema::dropIfExists('inventories_picking_types');
    }
};
