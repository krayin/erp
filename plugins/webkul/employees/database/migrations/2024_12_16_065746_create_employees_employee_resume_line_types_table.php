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
        Schema::create('employees_employee_resume_line_types', function (Blueprint $table) {
            $table->id();
            $table->integer('sort');
            $table->string('name');

            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_employee_resume_line_types');
    }
};
