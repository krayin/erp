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
        Schema::create('recruitments_candidate_skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('candidate_id')->constrained('recruitments_candidates')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('employees_skills')->onDelete('restrict');
            $table->foreignId('skill_level_id')->constrained('employees_skill_levels')->onDelete('restrict');
            $table->foreignId('skill_type_id')->constrained('employees_skill_types')->onDelete('restrict');
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_candidate_skills');
    }
};
