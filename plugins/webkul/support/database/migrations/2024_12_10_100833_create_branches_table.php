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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->string('name')->nullable(false);
            $table->string('tax_id')->unique()->nullable();
            $table->string('registration_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();

            $table->string('logo')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('founded_date')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('currency_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
