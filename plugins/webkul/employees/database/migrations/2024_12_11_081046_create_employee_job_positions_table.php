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
        Schema::create('employee_job_positions', function (Blueprint $table) {
            $table->id('id');
            $table->integer('sequence')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->boolean('status')->default(1);
            $table->date('open_date')->nullable();
            $table->integer('expected_employees')->nullable();
            $table->integer('no_of_employees')->nullable();
            $table->integer('no_of_recruitment')->nullable();

            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_job_positions');
    }
};
