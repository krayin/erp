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
        Schema::create('projects_task_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('projects_tasks')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('stage_id')
                ->nullable()
                ->constrained('projects_task_stages')
                ->nullOnDelete();

            $table->unique(['task_id', 'user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_task_users');
    }
};
