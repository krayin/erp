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
        Schema::create('inventories_scrap_tags', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->constrained('inventories_tags')
                ->cascadeOnDelete();

            $table->foreignId('scrap_id')
                ->constrained('inventories_scraps')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_scrap_tags');
    }
};
