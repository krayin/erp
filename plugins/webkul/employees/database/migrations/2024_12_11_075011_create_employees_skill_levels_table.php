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
        Schema::create('employees_skill_levels', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('Name');
            $table->integer('level')->nullable()->comment('Level');
            $table->boolean('default_level')->nullable()->comment('Default Levell');

            $table->unsignedBigInteger('skill_type_id')->nullable()->index()->comment('Skill Type');
            $table->unsignedBigInteger('creator_id')->nullable()->index()->comment('Created by');

            $table->foreign('skill_type_id')->references('id')->on('employees_skill_types')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_skill_levels');
    }
};
