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
        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('hr_skills')->onDelete('cascade');
            $table->foreignId('skill_level_id')->constrained('hr_skill_levels')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->text('notes')->nullable();
            $table->unique(['employee_id', 'skill_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_skills');
    }
};
