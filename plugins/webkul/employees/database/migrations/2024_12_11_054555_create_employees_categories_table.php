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
        Schema::create('employees_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique()->comment('Name');
            $table->string('color')->nullable()->comment('Color');

            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created by');

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_categories');
    }
};
