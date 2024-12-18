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
        Schema::create('employees_activities', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');

            $table->unsignedBigInteger('request_partner_id')->nullable()->comment('The partner who requested the activity');
            $table->unsignedBigInteger('recommended_activity_type_id')->nullable()->comment('The recommended activity type');
            $table->unsignedBigInteger('previous_activity_type_id')->nullable()->comment('The previous activity type');
            $table->unsignedBigInteger('activity_type_id')->nullable()->comment('The type of activity');
            $table->unsignedBigInteger('user_id')->nullable()->comment('The user who performed the activity');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created by');

            $table->string('name')->comment('The name of the activity');
            $table->text('summary')->nullable()->comment('The summary of the activity');
            $table->string('user_tz')->nullable()->comment('The timezone of the user who performed the activity');
            $table->date('date_deadline')->nullable()->comment('The deadline of the activity');
            $table->date('date_done')->nullable()->comment('The date when the activity was done');
            $table->text('note')->nullable()->comment('The note of the activity');
            $table->boolean('automated')->default(false)->comment('Whether the activity was automated');
            $table->boolean('active')->default(true)->comment('Whether the activity is active');

            $table->foreign('activity_type_id')->references('id')->on('activity_types')->onDelete('restrict');
            $table->foreign('recommended_activity_type_id')->references('id')->on('activity_types')->onDelete('set null');
            $table->foreign('previous_activity_type_id')->references('id')->on('activity_types')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('request_partner_id')->references('id')->on('partners_partners')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_plans');
    }
};
