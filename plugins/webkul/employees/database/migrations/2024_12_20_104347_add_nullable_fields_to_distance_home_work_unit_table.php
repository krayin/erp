<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees_employees', function (Blueprint $table) {
            $table->integer('distance_home_work')->default(0)->nullable()->change();
            $table->integer('km_home_work')->default(0)->nullable()->change();
            $table->string('distance_home_work_unit')->default('km')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees_employees', function (Blueprint $table) {
            $table->integer('distance_home_work')->default(0)->nullable(false)->change();
            $table->integer('km_home_work')->default(0)->nullable(false)->change();
            $table->string('distance_home_work_unit')->default('km')->nullable(false)->change();
        });
    }
};
