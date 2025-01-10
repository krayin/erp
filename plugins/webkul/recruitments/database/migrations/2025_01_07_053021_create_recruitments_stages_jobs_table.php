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
        Schema::create('recruitments_stages_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('stage_id')->nullable()->comment('Stage ID');
            $table->unsignedBigInteger('job_id')->nullable()->comment('Job ID');

            $table->foreign('stage_id')->references('id')->on('recruitments_stages')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('employees_job_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_stages_jobs');
    }
};
