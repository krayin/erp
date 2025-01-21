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
        Schema::create('time_off_leave_accrual_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('time_off_type_id')->nullable()->constrained('time_off_leave_types')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->integer('carryover_day')->nullable();
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('transition_mode');
            $table->string('accrued_gain_time');
            $table->string('carryover_date');
            $table->string('carryover_month')->nullable();
            $table->string('added_value_type')->nullable();
            $table->boolean('is_active')->nullable();
            $table->boolean('is_based_on_worked_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_off_leave_accrual_plans');
    }
};
