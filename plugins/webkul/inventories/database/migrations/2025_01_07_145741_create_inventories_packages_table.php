<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Webkul\Inventory\Enums\PackageUse;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('package_use')->default(PackageUse::DISPOSABLE);
            $table->date('pack_date')->nullable();

            $table->foreignId('package_type_id')
                ->nullable()
                ->constrained('inventories_package_types')
                ->nullOnDelete();

            $table->foreignId('location_id')
                ->nullable()
                ->constrained('inventories_locations')
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
        Schema::dropIfExists('inventories_packages');
    }
};
