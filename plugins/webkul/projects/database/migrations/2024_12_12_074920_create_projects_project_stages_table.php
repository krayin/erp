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
        Schema::create('projects_project_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_collapsed')->default(0);
            $table->integer('sort')->nullable();

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
        Schema::dropIfExists('projects_project_stages');
    }
};
