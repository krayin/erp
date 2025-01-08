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
        Schema::create('inventories_warehouse_resupplies', function (Blueprint $table) {
            $table->foreignId('supplied_warehouse_id')
                ->constrained('inventories_warehouses')
                ->cascadeOnDelete();

            $table->foreignId('supplier_warehouse_id')
                ->constrained('inventories_warehouses')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_warehouse_resupplies');
    }
};
