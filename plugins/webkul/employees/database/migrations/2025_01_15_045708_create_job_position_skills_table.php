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
        Schema::create('job_position_skills', function (Blueprint $table) {
            $table->unsignedBigInteger('job_position_id');
            $table->unsignedBigInteger('skill_id');

            $table->foreign('job_position_id')->references('id')->on('employees_job_positions')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('employees_skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_position_skills');
    }
};
