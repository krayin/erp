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
        Schema::table('analytic_records', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects_projects')
                ->nullOnDelete();

            $table->foreignId('task_id')
                ->nullable()
                ->constrained('projects_tasks')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytic_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_id');
            $table->dropConstrainedForeignId('task_id');
        });
    }
};
