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
        Schema::create('employees_skill_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->comment('Name');
            $table->string('color')->nullable()->comment('Color');
            $table->boolean('is_active')->default(false)->comment('Active Status');

            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');
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
        Schema::dropIfExists('employees_skill_types');
    }
};
