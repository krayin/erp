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
        Schema::create('recruitments_degrees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->integer('sort')->default(0)->nullable()->comment('Sort Order');
            $table->string('name')->comment('Name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_degrees');
    }
};
