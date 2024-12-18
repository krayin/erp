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
        Schema::create('employees_employee_resumes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('employee_resume_line_type_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created by');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('display_type');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreign('employee_id')->references('id')->on('employees_employees')->cascadeOnDelete();
            $table->foreign('employee_resume_line_type_id')->references('id')->on('employees_employee_resume_line_types')->nullOnDelete();
            $table->foreign('creator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_employee_resumes');
    }
};
