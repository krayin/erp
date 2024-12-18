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
        Schema::create('employees_employment_types', function (Blueprint $table) {
            $table->id();

            $table->integer('sort')->nullable()->comment('Sort order');
            $table->string('name')->comment('Employment type name');
            $table->string('code')->nullable()->comment('Employment type code');

            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created by');

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_employment_types');
    }
};
