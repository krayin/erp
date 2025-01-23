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
        Schema::create('employees_job_positions', function (Blueprint $table) {
            $table->id('id');

            $table->integer('sort')->nullable()->comment('Sort order');
            $table->integer('expected_employees')->nullable()->comment('Expected Employees');
            $table->integer('no_of_employee')->nullable()->comment('No of employees');
            $table->integer('no_of_recruitment')->nullable()->comment('No of recruitment');
            $table->unsignedBigInteger('department_id')->nullable()->comment('Department');
            $table->unsignedBigInteger('company_id')->nullable()->comment('Company');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');
            $table->unsignedBigInteger('employment_type_id')->nullable()->comment('Employment Type');

            $table->string('name')->comment('Job Position Name');
            $table->text('description')->nullable()->comment('Job Description');
            $table->text('requirements')->nullable()->comment('Requirements');
            $table->boolean('is_active')->default(false)->comment('Active Status');

            $table->foreign('department_id')->references('id')->on('employees_departments')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('employment_type_id')->references('id')->on('employees_employment_types')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_job_positions');
    }
};
