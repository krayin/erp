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
        Schema::create('partners_partner_tag', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->constrained('partners_tags')
                ->cascadeOnDelete();

            $table->foreignId('partner_id')
                ->constrained('partners_partners')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners_partner_tag');
    }
};
