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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('type');
            $table->string('input_type')->nullable();
            $table->boolean('is_multiselect')->default(0);
            $table->json('datalist')->nullable();
            $table->json('options')->nullable();
            $table->json('form_settings')->nullable();
            $table->boolean('use_in_table')->default(0);
            $table->json('table_settings')->nullable();
            $table->json('infolist_settings')->nullable();
            $table->integer('sort')->nullable();
            $table->string('customizable_type');
            $table->unique(['code', 'customizable_type']);
            $table->softDeletes();
            $table->timestamps();

            $table->index('code');
            $table->index('sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
