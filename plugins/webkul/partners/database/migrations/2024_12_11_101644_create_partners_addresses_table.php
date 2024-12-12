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
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('street1');
            $table->string('street2')->nullable();
            $table->string('city');
            $table->string('zip');

            $table->foreignId('state_id')
                ->nullable()
                ->constrained('states')
                ->restrictOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->restrictOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->constrained('partners_partners')
                ->cascadeOnDelete();

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
