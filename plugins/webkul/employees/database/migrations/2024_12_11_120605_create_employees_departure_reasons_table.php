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
        Schema::create('employees_departure_reasons', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->integer('reason_code')->nullable();
            $table->string('name')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_departure_reasons');
    }
};
