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
        Schema::create('inventories_product_quantity_relocations', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();

            $table->foreignId('destination_location_id')
                ->nullable()
                ->constrained('inventories_locations', 'id', 'dest_loc_fk')
                ->nullOnDelete();

            $table->foreignId('destination_package_id')
                ->constrained('inventories_packages', 'id', 'dest_pkg_fk')
                ->restrictOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users', 'id', 'creator_fk')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_product_quantity_relocations');
    }
};
