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
        Schema::table('employees_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('attendance_manager_id')->nullable()->after('job_id');

            $table->foreign('attendance_manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_employees', function (Blueprint $table) {
            //
        });
    }
};
