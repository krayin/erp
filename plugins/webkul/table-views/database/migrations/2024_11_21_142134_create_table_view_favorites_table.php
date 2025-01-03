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
        Schema::create('table_view_favorites', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_favorite')->default(1);
            $table->string('view_type');
            $table->string('view_key');
            $table->string('filterable_type');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['view_type', 'view_key', 'filterable_type', 'user_id'], 'tbl_view_fav_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_view_favorites');
    }
};
