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
        Schema::create('employees_skills', function (Blueprint $table) {
            $table->id();

            $table->integer('sort')->nullable();
            $table->string('name');

            $table->unsignedBigInteger('skill_type_id')->index();
            $table->unsignedBigInteger('creator_id')->nullable()->index();

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
        Schema::dropIfExists('employees_skills');
    }
};
