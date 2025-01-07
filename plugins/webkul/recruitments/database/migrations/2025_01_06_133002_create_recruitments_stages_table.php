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
        Schema::create('recruitments_stages', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable()->comment('Sort order of the stage');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->string('name')->comment('Name of the stage');
            $table->string('legend_blocked')->comment('Legend for blocked applications');
            $table->string('legend_done')->comment('Legend for done applications');
            $table->string('legend_normal')->comment('Legend for normal applications');
            $table->text('requirements')->nullable()->comment('Requirements for the stage');
            $table->string('hired_stage')->nullable()->comment('Stage to move the application to when hired');
            $table->boolean('fold')->default(false)->comment('Whether the stage is folded');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_stages');
    }
};
