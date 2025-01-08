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
        Schema::create('warehouses_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('code')->nullable();
            $table->integer('sort')->nullable();
            $table->string('reception_steps');
            $table->string('delivery_steps');

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->foreignId('partner_address_id')
                ->nullable()
                ->constrained('partners_addresses')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->unique(['company_id', 'name']);
            $table->unique(['company_id', 'code']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses_warehouses');
    }
};
