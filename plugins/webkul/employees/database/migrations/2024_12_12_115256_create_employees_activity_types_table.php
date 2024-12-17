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
        Schema::create('employees_activity_types', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->integer('delay_count')->nullable();
            $table->string('delay_unit')->nullable();
            $table->string('delay_from')->nullable();
            $table->string('icon')->nullable();
            $table->string('decoration_type')->nullable();
            $table->string('chaining_type');
            $table->string('model_type')->nullable();
            $table->string('category')->nullable();
            $table->string('name');
            $table->text('summary')->nullable();
            $table->text('default_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('keep_done')->default(false);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('default_user_id')->nullable();
            $table->unsignedBigInteger('activity_plan_id')->nullable();
            $table->unsignedBigInteger('triggered_next_type_id')->nullable();

            $table->foreign('activity_plan_id')->references('id')->on('employees_activity_plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('default_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('triggered_next_type_id')->references('id')->on('employees_activity_types')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_activity_types');
    }
};
