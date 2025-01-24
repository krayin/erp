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
        Schema::create('activity_plans', function (Blueprint $table) {
            $table->id();

            $table->string('plugin')->nullable()->comment('Plugin name');
            $table->string('name')->comment('Name of the plan');
            $table->boolean('is_active')->nullable()->default(false)->comment('Status');

            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');
            $table->unsignedBigInteger('company_id')->nullable()->comment('Company');

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_');
    }
};
