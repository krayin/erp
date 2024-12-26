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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('followed_at')->nullable();
            $table->timestamps();

            $table->unique(['followable_type', 'followable_id', 'user_id']);
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
