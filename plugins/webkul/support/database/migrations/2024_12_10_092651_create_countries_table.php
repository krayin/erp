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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('code', 2)->nullable();
            $table->string('name')->nullable();
            $table->boolean('state_required')->default(false);
            $table->boolean('zip_required')->default(false);

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null')->onUpdate('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });

        Schema::dropIfExists('countries');
    }
};
