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
        Schema::table('employees_job_positions', function (Blueprint $table) {
            $table->unsignedBigInteger('address_id')->nullable()->after('company_id')->comment('Job Location');
            $table->unsignedBigInteger('manager_id')->nullable()->after('address_id')->comment('Department Manager');
            $table->unsignedBigInteger('industry_id')->nullable()->after('manager_id')->comment('Partner Industry');
            $table->unsignedBigInteger('recruiter_id')->nullable()->after('industry_id')->comment('Recruiter');
            $table->integer('no_of_hired_employee')->nullable()->after('recruiter_id')->comment('No of Hired Employee');
            $table->timestamp('date_from')->nullable()->after('no_of_hired_employee')->comment('Date From');
            $table->timestamp('date_to')->nullable()->after('date_from')->comment('Date To');

            $table->foreign('address_id')->references('id')->on('partners_partners')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('employees_employees')->onDelete('set null');
            $table->foreign('industry_id')->references('id')->on('partners_industries')->onDelete('set null');
            $table->foreign('recruiter_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_job_positions', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['industry_id']);
            $table->dropForeign(['recruiter_id']);

            $table->dropColumn('address_id');
            $table->dropColumn('manager_id');
            $table->dropColumn('industry_id');
            $table->dropColumn('recruiter_id');
            $table->dropColumn('no_of_hired_employee');
            $table->dropColumn('date_from');
            $table->dropColumn('date_to');
        });
    }
};
