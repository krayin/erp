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
        Schema::create('employees_activity_plans', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->string('name')->nullable();
            $table->boolean('is_active')->nullable()->default(true);

            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('employees_departments')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_activity_plans');
    }
};
