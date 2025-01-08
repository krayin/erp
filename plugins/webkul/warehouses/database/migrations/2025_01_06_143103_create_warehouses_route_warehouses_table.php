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
        Schema::create('warehouses_route_warehouses', function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained('warehouses_warehouses')
                ->cascadeOnDelete();

            $table->foreignId('route_id')
                ->constrained('warehouses_routes')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses_route_warehouses');
    }
};
