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
        Schema::create('employees_employee_skills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('skill_id')->nullable();
            $table->unsignedBigInteger('skill_level_id')->nullable();
            $table->unsignedBigInteger('skill_type_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created by');

            $table->foreign('employee_id')->references('id')->on('employees_employees')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('employees_skills')->onDelete('restrict');
            $table->foreign('skill_level_id')->references('id')->on('employees_skill_levels')->onDelete('restrict');
            $table->foreign('skill_type_id')->references('id')->on('employees_skill_types')->onDelete('restrict');
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
        Schema::dropIfExists('employees_employee_skills');
    }
};
