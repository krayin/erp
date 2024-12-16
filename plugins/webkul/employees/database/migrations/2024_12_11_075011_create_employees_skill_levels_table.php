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
            $table->string('name');
            $table->integer('level')->default(0);
            $table->boolean('default_level')->default(0);

            $table->unsignedBigInteger('skill_type_id')->index();
            $table->foreign('skill_type_id')->references('id')->on('employees_skill_types')->onDelete('cascade');

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
