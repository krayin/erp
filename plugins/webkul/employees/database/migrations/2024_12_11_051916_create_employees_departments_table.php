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
        Schema::create('employees_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('master_department_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->string('name')->nullable();
            $table->string('complete_name')->nullable();
            $table->string('parent_path')->nullable();
            $table->text('color')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('employees_departments')->onDelete('set null');
            $table->foreign('master_department_id')->references('id')->on('employees_departments')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_departments');
    }
};
