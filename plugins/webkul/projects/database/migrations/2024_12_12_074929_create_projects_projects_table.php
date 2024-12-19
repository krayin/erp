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
        Schema::create('projects_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('tasks_label')->nullable();
            $table->text('description')->nullable();
            $table->string('visibility')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort')->nullable()->index();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('allocated_hours')->nullable();
            $table->boolean('allow_timesheets')->default(0);
            $table->boolean('allow_milestones')->default(0);
            $table->boolean('allow_task_dependencies')->default(0);
            $table->boolean('is_active')->default(1);

            $table->foreignId('stage_id')
                ->nullable()
                ->constrained('projects_project_stages')
                ->restrictOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_projects');
    }
};
