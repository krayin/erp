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
            $table->string('category')->nullable();
            $table->jsonb('name');
            $table->jsonb('summary')->nullable();
            $table->jsonb('default_note')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->boolean('keep_done')->nullable()->default(false);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('default_user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('default_user_id')->references('id')->on('users')->onDelete('set null');

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
