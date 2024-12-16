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
        Schema::create('projects_user_project_favorites', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->constrained('projects_projects')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_user_project_favorites');
    }
};
