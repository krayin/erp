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
        Schema::create('chatter_followers', function (Blueprint $table) {
            $table->id();
            $table->morphs('followable');
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->timestamp('followed_at')->nullable();
            $table->timestamps();

            $table->unique(['followable_type', 'followable_id', 'partner_id'], 'chatter_followers_unique');

            $table->foreign('partner_id')->references('id')->on('partners_partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatter_followers');
    }
};
