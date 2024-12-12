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
        Schema::create('branch_addresses', function (Blueprint $table) {
            $table->id();

            $table->string('street1');
            $table->string('street2')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->boolean('is_primary')->default(1);

            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('country_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_addresses');
    }
};
