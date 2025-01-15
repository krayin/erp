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
        Schema::create('inventories_route_packagings', function (Blueprint $table) {
            $table->foreignId('route_id')
                ->constrained('inventories_routes')
                ->cascadeOnDelete();

            $table->foreignId('packaging_id')
                ->constrained('products_packagings')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_route_packagings');
    }
};
