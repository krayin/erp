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
            $table->string('name');
            $table->string('location_type');
            $table->string('location_number')->nullable();
            $table->boolean('is_active')->default(false);

            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
