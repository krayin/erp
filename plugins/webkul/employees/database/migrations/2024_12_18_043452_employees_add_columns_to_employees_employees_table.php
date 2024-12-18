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
            $table->string('tz')->nullable()->comment('Employee Timezone')->after('coach_id');
            $table->string('work_permit')->nullable()->comment('Work permit document')->after('tz');
            $table->unsignedBigInteger('address_id')->nullable()->comment('Company address ID')->after('work_permit');
            $table->unsignedBigInteger('leave_manager_id')->nullable()->comment('Leave manager ID')->after('address_id');

            $table->foreign('address_id')
                ->references('id')
                ->on('company_addresses')
                ->onDelete('cascade');

            $table->foreign('leave_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_employees', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropForeign(['leave_manager_id']);

            $table->dropColumn([
                'tz',
                'work_permit',
                'address_id',
                'leave_manager_id',
            ]);
        });
    }
};
