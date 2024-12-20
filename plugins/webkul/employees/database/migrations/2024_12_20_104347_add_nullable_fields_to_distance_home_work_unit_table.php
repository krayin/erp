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
            $table->string('distance_home_work')->nullable()->change();
            $table->string('km_home_work')->nullable()->change();
            $table->string('distance_home_work_unit')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_employees', function (Blueprint $table) {
            $table->string('distance_home_work')->nullable(false)->change();
            $table->string('km_home_work')->nullable(false)->change();
            $table->string('distance_home_work_unit')->nullable(false)->change();
        });
    }
};
