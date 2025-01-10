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
        Schema::create('employees_addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id')->comment('Employee');
            $table->unsignedBigInteger('state_id')->nullable()->comment('State');
            $table->unsignedBigInteger('country_id')->nullable()->comment('Country');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');
            $table->unsignedBigInteger('partner_address_id')->comment('Partner Address');

            $table->string('type')->nullable()->comment('Address Type');
            $table->string('street1')->nullable()->comment('Street 1');
            $table->string('street2')->nullable()->comment('Street 2');
            $table->string('city')->nullable()->comment('City');
            $table->string('zip')->nullable()->comment('zip');
            $table->boolean('is_primary')->default(0)->comment('Primary Address');

            $table->foreign('employee_id')->references('id')->on('employees_employees')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('partner_address_id')->references('id')->on('partners_addresses')->onDelete('restrict');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_addresses');
    }
};
