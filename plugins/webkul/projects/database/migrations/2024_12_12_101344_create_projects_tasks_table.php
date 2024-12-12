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
        Schema::create('projects_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->string('color')->nullable();
            $table->string('priority')->nullable()->index();
            $table->string('state')->index();
            $table->integer('sort')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_recurring')->default(0);
            $table->datetime('deadline')->nullable()->index();
            $table->decimal('working_hours_open')->nullable();
            $table->decimal('working_hours_close')->nullable();
            $table->decimal('allocated_hours')->nullable();
            $table->decimal('remaining_hours')->nullable();
            $table->decimal('effective_hours')->nullable();
            $table->decimal('total_hours_spent')->nullable();
            $table->decimal('overtime')->nullable();
            $table->decimal('progress')->nullable();

            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects_projects')
                ->nullOnDelete();

            $table->foreignId('stage_id')
                ->nullable()
                ->constrained('projects_task_stages')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('projects_tasks')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
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
        Schema::dropIfExists('projects_tasks');
    }
};
