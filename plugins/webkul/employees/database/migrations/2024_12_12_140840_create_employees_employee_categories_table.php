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
        Schema::create('employees_employee_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->foreign('employee_id')->references('id')->on('employees_employees')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('employees_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_employee_categories');
    }
};
