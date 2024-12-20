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
        Schema::create('partners_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type');

            $table->unsignedBigInteger('state_id')->nullable()->comment('State');
            $table->unsignedBigInteger('country_id')->nullable()->comment('Country');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');
            $table->unsignedBigInteger('partner_id')->comment('Partner');

            $table->string('name')->nullable()->comment('Name');
            $table->string('email')->nullable()->comment('Email');
            $table->string('phone')->nullable()->comment('Phone');
            $table->string('street1')->nullable()->comment('Street 1');
            $table->string('street2')->nullable()->comment('Street 2');
            $table->string('city')->nullable()->comment('City');
            $table->string('zip')->nullable()->comment('Zip');

            $table->foreign('state_id')->references('id')->on('states')->onDelete('restrict');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('partner_id')->references('id')->on('partners_partners')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners_addresses');
    }
};
