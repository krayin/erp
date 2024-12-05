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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street1');
            $table->string('street2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            $table->string('tax_id')->unique();
            $table->string('company_id')->unique();
            $table->string('currency');
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->string('email')->unique();
            $table->string('website')->nullable();
            $table->string('email_domain')->nullable();
            $table->string('color')->nullable();
            $table->string('logo')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
