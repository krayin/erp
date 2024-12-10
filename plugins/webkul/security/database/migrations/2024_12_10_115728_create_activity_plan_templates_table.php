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
            $table->integer('delay_count')->nullable();
            $table->integer('sequence')->nullable();
            $table->string('delay_unit')->nullable();
            $table->string('delay_from')->nullable();
            $table->string('summary')->nullable();
            $table->string('responsible_type')->nullable();
            $table->text('note')->nullable();

            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('activity_type_id')->nullable();
            $table->unsignedBigInteger('responsible_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();


            $table->foreign('plan_id')->references('id')->on('activity_plans')->onDelete('set null');
            $table->foreign('responsible_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('activity_type_id')->references('id')->on('activity_types')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

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
