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
            $table->string('tz')->nullable()->comment('Employee Timezone');
            $table->string('work_permit')->nullable()->comment('Work permit document');
            $table->unsignedBigInteger('address_id')->nullable()->comment('Company address id');
            $table->unsignedBigInteger('leave_manager_id')->nullable()->comment('Leave manager id');

            $table->foreign('leave_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('company_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_employees', function (Blueprint $table) {
            $table->dropForeign(['leave_manager_id']);
            $table->dropForeign(['address_id']);

            $table->dropColumn(['leave_manager_id', 'address_id', 'work_permit', 'tz']);
        });
    }
};
