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
        Schema::create('projects_project_tag', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->constrained('projects_tags')
                ->cascadeOnDelete();

            $table->foreignId('project_id')
                ->constrained('projects_projects')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_project_tag');
    }
};
