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
        Schema::create('employees_calendar_attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->string('name');
            $table->string('day_of_week');
            $table->string('day_period');
            $table->string('week_type')->nullable();
            $table->string('display_type')->nullable();
            $table->string('date_from')->nullable();
            $table->string('date_to')->nullable();
            $table->string('durations_days')->nullable();
            $table->string('hour_from');
            $table->string('hour_to');

            $table->unsignedBigInteger('calendar_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('calendar_id')->references('id')->on('employees_calendars')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_calendar_attendances');
    }
};
