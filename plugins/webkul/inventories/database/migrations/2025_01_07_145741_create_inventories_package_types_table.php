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
        Schema::create('inventories_package_types', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->string('name');
            $table->string('barcode')->nullable()->unique();
            $table->decimal('height', 15, 4)->nullable()->default(0);
            $table->decimal('width', 15, 4)->nullable()->default(0);
            $table->decimal('length', 15, 4)->nullable()->default(0);
            $table->decimal('base_weight', 15, 4)->nullable()->default(0);
            $table->decimal('max_weight', 15, 4)->nullable()->default(0);
            $table->string('shipper_package_code')->nullable();
            $table->string('package_carrier_type')->nullable();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

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
        Schema::dropIfExists('inventories_package_types');
    }
};
