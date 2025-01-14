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
            $table->unsignedBigInteger('address_id')->nullable()->comment('Job Location')->after('company_id');
            $table->unsignedBigInteger('manager_id')->nullable()->comment('Department Manager')->after('address_id');
            $table->unsignedBigInteger('industry_id')->nullable()->comment('Partner Industry')->after('manager_id');

            $table->foreign('address_id')->references('id')->on('partners_partners')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('employees_employees')->onDelete('set null');
            $table->foreign('industry_id')->references('id')->on('partners_industries')->onDelete('set null');
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

            $table->dropColumn('address_id');
            $table->dropColumn('manager_id');
            $table->dropColumn('industry_id');
        });
    }
};
