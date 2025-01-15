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
        Schema::table('products_packagings', function (Blueprint $table) {
            $table->foreignId('package_type_id')
                ->nullable()
                ->constrained('inventories_package_types')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_packagings', function (Blueprint $table) {
            $table->dropColumn('package_type_id');

            $table->dropColumn('package_type_id');
        });
    }
};
