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
        Schema::create('inventories_scraps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('origin')->nullable();
            $table->string('state')->nullable();
            $table->decimal('qty', 15, 4)->default(0);
            $table->boolean('should_replenish')->default(0);
            $table->date('date_done')->nullable();

            $table->foreignId('product_id')
                ->constrained('products_products')
                ->restrictOnDelete();

            $table->foreignId('uom_id')
                ->constrained('unit_of_measures')
                ->restrictOnDelete();

            $table->foreignId('lot_id')
                ->nullable()
                ->constrained('inventories_lots')
                ->nullOnDelete();

            $table->foreignId('package_id')
                ->nullable()
                ->constrained('inventories_packages')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('operation_id')
                ->nullable()
                ->constrained('inventories_operations')
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
        Schema::dropIfExists('inventories_scraps');
    }
};
