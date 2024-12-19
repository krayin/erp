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
        Schema::create('activity_plan_templates', function (Blueprint $table) {
            $table->id();

            $table->integer('sort')->nullable()->comment('Sort Order');
            $table->unsignedBigInteger('plan_id')->comment('Plan ID');
            $table->unsignedBigInteger('activity_type_id')->comment('Activity Type');
            $table->unsignedBigInteger('responsible_id')->nullable()->comment('Responsible');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');

            $table->integer('delay_count')->nullable()->comment('Delay count');
            $table->string('delay_unit')->comment('Delay unit');
            $table->string('delay_from')->comment('Delay From');
            $table->text('summary')->nullable()->comment('Summary');
            $table->string('responsible_type')->comment('Responsible Type');
            $table->text('note')->nullable()->comment('Note');

            $table->foreign('plan_id')->references('id')->on('activity_plans')->onDelete('cascade');
            $table->foreign('activity_type_id')->references('id')->on('activity_types')->onDelete('restrict');
            $table->foreign('responsible_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_plan_templates');
    }
};
