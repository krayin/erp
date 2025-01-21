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
        Schema::create('time_off_leave_accrual_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable()->comment('Sort order');
            $table->foreignId('accrual_plan_id')->constrained('time_off_leave_accrual_plans')->cascadeOnDelete();
            $table->integer('start_count')->nullable()->comment('Start After');
            $table->integer('first_day')->nullable()->comment('First Day');
            $table->integer('second_day')->nullable()->comment('Second Day');
            $table->integer('first_month_day')->nullable()->comment('First Month Day');
            $table->integer('second_month_day')->nullable()->comment('Second Month Day');
            $table->integer('yearly_day')->nullable()->comment('Yearly Day');
            $table->integer('postpone_max_days')->nullable()->comment('Postpone Max Days');
            $table->integer('accrual_validity_count')->nullable()->comment('Accrual Validity Count');
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('start_type')->comment('Start Type');
            $table->string('added_value_type')->comment('Added Value Type');
            $table->string('frequency')->comment('Frequency');
            $table->string('week_day')->comment('Week Day');
            $table->string('first_month')->nullable()->comment('First Month');
            $table->string('second_month')->nullable()->comment('Second Month');
            $table->string('yearly_month')->nullable()->comment('Yearly Month');
            $table->string('action_with_unused_accruals')->comment('Action With Unused Accruals');
            $table->string('accrual_validity_type')->comment('Accrual Validity Type');
            $table->integer('added_value')->comment('Added Value');
            $table->integer('maximum_leave')->nullable()->comment('Maximum Leave');
            $table->integer('maximum_leave_yearly')->nullable()->comment('Maximum Leave Yearly');
            $table->boolean('cap_accrued_time')->nullable()->comment('Cap Accrued Time');
            $table->boolean('cap_accrued_time_yearly')->nullable()->comment('Cap Accrued Time Yearly');
            $table->boolean('accrual_validity')->nullable()->comment('Accrual Validity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_off_leave_accrual_levels');
    }
};
