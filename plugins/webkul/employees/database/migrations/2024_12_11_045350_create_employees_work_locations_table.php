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
        Schema::create('employees_work_locations', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('Work Location');
            $table->string('location_type')->comment('Cover Image');
            $table->string('location_number')->nullable()->comment('Location Number');
            $table->boolean('is_active')->nullable()->default(false)->comment('Status');

            $table->unsignedBigInteger('company_id')->comment('Company');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created by');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_work_locations');
    }
};
